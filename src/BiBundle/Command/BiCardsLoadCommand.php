<?php

namespace BiBundle\Command;

use BiBundle\Entity\Argument;
use BiBundle\Entity\ArgumentFilter;
use BiBundle\Entity\Card;
use BiBundle\Entity\CardCarouselImage;
use BiBundle\Entity\CardCategory;
use BiBundle\Entity\CardRepresentation;
use BiBundle\Entity\File;
use BiBundle\Entity\Representation;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BiCardsLoadCommand extends ContainerAwareCommand
{
    const REPRESENTATION_DIAGRAM = 'diagram';
    const REPRESENTATION_LINE = 'line';
    const REPRESENTATION_COLUMN = 'column';
    const REPRESENTATION_PIE = 'pie';
    const REPRESENTATION_FUNNEL = 'funnel';

    CONST CONTROL_TYPE_MULTISELECT = 'multiselect';
    const CONTROL_TYPE_CHECKBOX = 'checkbox';
    const CONTROL_TYPE_RADIOBUTTON = 'radiobutton';
    const CONTROL_TYPE_DATESLIDER = 'dateslider';
    const CONTROL_TYPE_NDS = 'nds';
    const CONTROL_TYPE_ONESELECT = 'oneselect';

    /** @var  string */
    private $webUploadPath;

    /** @var  string */
    private $webRootPath;

    /** @var  string */
    private $cardImagesPath;
    /** @var  EntityManager */
    private $entityManager;

    private $addRepresentations = false;

    /** @var  string */
    private $cardImagesDestinationPath;

    protected function configure()
    {
        $this
            ->setName('bi:cards:load')
            ->setDescription('Load cards')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force cards load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force')) {
            throw new \Exception('To force loading cards use the --force option.');
        }

        $this->webRootPath = rtrim($this->getContainer()->getParameter('web_root_directory'), '/');
        $this->webUploadPath = rtrim($this->getContainer()->getParameter('web_upload_directory'), '/');
        $this->cardImagesPath = '/images/cards/';
        $this->cardImagesDestinationPath = '/' . $this->webUploadPath . '/images/cards/';

        $this->entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        if (count($this->entityManager->getRepository(Card::class)->findAll()) > 0) {
            throw new \ErrorException('Card table is not empty');
        }

        foreach ($this->data as $cardData) {
            if ($this->entityManager->getRepository(Card::class)->findBy(['name' => $cardData['name']])) {
                throw new \ErrorException("Card with name '{$cardData['name']}' exists");
            }
            $output->writeln("Adding card '{$cardData['name']}'");
            $card = new Card();
            $this->entityManager->persist($card);
            $card->setName($cardData['name']);
            $card->setDescription($cardData['description']);
            if (isset($cardData['rating'])) {
                $card->setRating($cardData['rating']);
            } else {
                $card->setRating(rand(4, 20) * 5);
            }
            if (isset($cardData['price'])) {
                $card->setPrice($cardData['price']);
            } else {
                $card->setPrice(rand(4, 20) * 10);
            }
            if (isset($cardData['category_name'])) {
                $card->setCardCategory(
                    $this->entityManager->getRepository(CardCategory::class)->findOneBy(['name' => $cardData['category_name']])
                );
            }
            $card->setDescriptionLong($cardData['description_long']);
            $card->setAuthor($cardData['author']);
            if ($this->addRepresentations && isset($cardData['representations'])) {
                $this->addRepresentations($card, $cardData['representations']);
            }
            $this->entityManager->flush($card);
            $this->addImages($card, $cardData);
            $card->setLocale('en');
            $card->setName($cardData['name_en']);
            $card->setDescription($cardData['description_en']);
            $card->setDescriptionLong($cardData['description_long_en']);
            $card->setAuthor($cardData['author_en']);
            if (isset($cardData['price_en'])) {
                $card->setPrice($cardData['price_en']);
            } else {
                $card->setPrice(number_format($card->getPrice() / 60, 2, '.', ''));
            }
            $this->entityManager->flush($card);
            foreach ($cardData['arguments'] as $argumentData) {
                $argument = new Argument();
                $this->entityManager->persist($argument);
                $card->addArgument($argument);
                $argument->setCard($card);
                $argument->setCode($argumentData['code']);
                $argument->setName($argumentData['name']);
                $argument->setDescription($argumentData['description']);
                if (isset($argumentData['dimension'])) {
                    $argument->setDimension($argumentData['dimension']);
                }
                $this->entityManager->flush($argument);
                $argument->setLocale('en');
                $argument->setName($argumentData['name_en']);
                $argument->setDescription($argumentData['description_en']);
                $this->entityManager->flush($argument);
                if (isset($argumentData['is_filter']) && $argumentData['is_filter']) {
                    $this->createSimpleArgumentFilter($argument, $argumentData);
                }
            }
        }
        $output->writeln('Done.');
    }

    private function addImages(Card $card, array $cardData)
    {
        if (isset($cardData['image_file'])) {
            $this->checkImageFile($cardData['image_file']);
            $imageFile = $this->createImageFile($cardData['image_file']);
            $card->setImageFile($imageFile);
        }
        if (isset($cardData['carousel_files'])) {
            $priority = 0;
            foreach ($cardData['carousel_files'] as $carouselFilename) {
                $this->createCarouselImage($card, $carouselFilename, $priority++);
            }
        }
        $this->entityManager->flush($card);
    }

    private function createImageFile($filename)
    {
        $sourcePath = $this->webRootPath . $this->cardImagesPath . $filename;
        $destinationPath = $this->webRootPath . $this->cardImagesDestinationPath . $filename;
        if (!copy($sourcePath, $destinationPath)) {
            throw new \ErrorException("Failed to copy $sourcePath to $destinationPath");
        }
        $file = new File();
        $file->setPath($this->cardImagesDestinationPath . $filename);
        $this->getContainer()->get('bi.file.service')->create($file);

        return $file;
    }

    private function createCarouselImage(Card $card, $filename, $priority)
    {
        $carouselImage = new CardCarouselImage();
        $imageFile = $this->createImageFile($filename);
        $carouselImage->setFile($imageFile);
        $carouselImage->setCard($card);
        $carouselImage->setPriority($priority);
        $this->entityManager->persist($carouselImage);
        $this->entityManager->flush($carouselImage);
    }

    private function checkImageFile($filename)
    {
        $path = $this->webRootPath . '/' . $this->cardImagesPath . $filename;
        if (!file_exists($path)) {
            throw new \ErrorException("Image file '$filename' not found");
        } elseif (!is_file($path)) {
            throw new \ErrorException("'$filename' is not a file");
        }
    }

    private function addRepresentations(Card $card, $representations)
    {
        foreach ($representations as $representation) {
            $cardRepresentation = new CardRepresentation();
            $cardRepresentation->setCard($card);
            $cardRepresentation->setRepresentation($this->entityManager->getRepository(Representation::class)->findOneBy(['code' => $representation]));
            $this->entityManager->persist($cardRepresentation);
            $this->entityManager->flush($cardRepresentation);
        }
    }

    private function createSimpleArgumentFilter(Argument $argument, array $argumentData)
    {
        $argumentFilter = new ArgumentFilter();
        $label = isset($argumentData['filter_label']) ? $argumentData['filter_label'] : $argument->getName();
        $argumentFilter->setLabel($label);
        $argumentFilter->setCard($argument->getCard());
        $argument->addArgumentFilter($argumentFilter);
        $this->entityManager->persist($argumentFilter);
        $this->entityManager->flush($argumentFilter);
        $this->entityManager->flush($argument);
    }

    private $data = [
        [
            'name' => "Дебеторская задолженность",
            'name_en' => "Receivables",
            'description' => "Сумма задолженности перед компанией",
            'description_en' => "The amount of debt owed to the company",
            'description_long' => "Дебиторская задолженность относится к оплаченным счетам компании или это деньги компании причитающихся от своих клиентов. Дебеторскя задолженность предсталяет собой кредит, представленный компанией и который должен быть оплачен за короткий период времени, начиная от нескольких ней до года.",
            'description_long_en' => "Accounts receivable refers to the outstanding invoices a company has or the money the company is owed from its clients. Receivables essentially represent a line of credit extended by a company and due within a relatively short time period, ranging from a few days to a year.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'price' => 75,
            'price_en' => 1.20,
            'rating' => 5,
            'image_file' => '4-1.png',
            'carousel_files' => ['4-1.png', '4-2.png', '4-3.png', '4-3.png'],
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'representations' => [self::REPRESENTATION_PIE, self::REPRESENTATION_LINE, self::REPRESENTATION_DIAGRAM],
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Организация',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Сумма задолженности',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Контрагент',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Дата',
                    'name_en' => '',
                    'dimension' => 'X',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
        [
            'name' => "Кредиторская задолженность",
            'name_en' => "Accounts payable",
            'description' => "Платежи, причитающиеся от компании к поставщикам и другим кредиторам",
            'description_en' => "Payments owed by the company to suppliers and other creditors",
            'description_long' => "Кредиторская задолженность это сумма всех краткосрочных обязательств за предоставленые услуги и продукцию. В случае если кредиторская задолженность не выплачивается, то кредиторская задолженность считается не оплаченной, что может вызвать штраф или выплату процентов.",
            'description_long_en' => "Accounts payable is the aggregate amount of an entity's short-term obligations to pay suppliers for products and services which the entity purchased on credit. If accounts payable are not paid within the payment terms agreed to with the supplier, the payables are considered to be in default, which may trigger a penalty or interest payment, or the revocation or curtailment of additional credit from the supplier.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'price' => 100,
            'price_en' => 1.50,
            'rating' => 5,
            'image_file' => '4-1.png',
            'carousel_files' => ['4-1.png', '4-2.png', '4-3.png', '4-3.png'],
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'representations' => [self::REPRESENTATION_PIE, self::REPRESENTATION_LINE, self::REPRESENTATION_DIAGRAM],
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Организация',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Сумма задолженности',
                    'name_en' => '',
                    'dimension' => 'Y',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Контрагент',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Дата',
                    'name_en' => '',
                    'dimension' => 'X',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
        [
            'name' => "Коэффициент рентабельности продаж (ROS)",
            'name_en' => "Return on sales (ROS)",
            'description' => "Оценка операционной деятельности хозяйствующего субъекта",
            'description_en' => "The evaluation an entity's operating performance",
            'description_long' => "Рентабельность продаж, которую часто называют операционной прибылью, является финансовым показателем, который показывает, насколько эффективно компания получает прибыль от своих доходов. Другими словами, он измеряет производительность компании, анализируя, какой процент от общей выручки компании фактически преобразуются в прибыль компании.",
            'description_long_en' => "Return on sales, often called the operating profit margin, is a financial ratio that calculates how efficiently a company is at generating profits from its revenue. In other words, it measures a company’s performance by analyzing what percentage of total company revenues are actually converted into company profits. Since the return on sales equation measures the percentage of sales that are converted to income, it shows how well the company is producing its core products or services and how well the management teams is running it.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '1-1.png',
            'carousel_files' => ['1-1.png', '1-2.png'],
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'NI',
                    'name' => 'Прибыль',
                    'name_en' => 'Net Income',
                    'description' => 'Величина прибыли до уплаты процентов и налогообложения',
                    'description_en' => 'Net Income before interest and tax',
                ],
                [
                    'code' => 'S',
                    'name' => 'Объем продаж',
                    'name_en' => 'Sales',
                    'description' => 'Выручка от реализации',
                    'description_en' => 'Revenues from sales',
                ],
                [
                    'code' => '',
                    'name' => 'Проект',
                    'name_en' => 'Project',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_CHECKBOX,
                    'is_filter' => true,
                ],
                [
                    'code' => '',
                    'name' => 'Период',
                    'name_en' => 'Period',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_DATESLIDER,
                    'is_filter' => true,
                ]
            ]
        ],
        [
            'name' => 'Коэффициент общей ликвидности (CR)',
            'name_en' => 'Current ratio (CR)',
            'description' => 'Отражает способность предприятия погашать все краткосрочные и долгосрочные финансовые обязательства',
            'description_en' => 'Shows a company\'s ability to pay short-term and long-term obligations',
            'description_long' => 'Коэффициент общей ликвидности (коэффициент текущей ликвидности, коэффициент покрытия, current ratio) - характеризует степень покрытия оборотных активов оборотными пассивами, и применяется для оценки способности предприятия выполнить свои краткосрочные обязательства за счет имеющихся оборотных активов. Чем показатель больше, тем выше платежеспособность компании. Коэффициент характеризует платежеспособность предприятия не только на данный момент, но и в случае чрезвычайных обстоятельств. Следует отметить, что данный коэффициент не всегда дает полную картину. Предприятия, у которых материально-производственные запасы невелики, а деньги по векселям к оплате получить легко, могут спокойно действовать с более низким значением коэффициента, чем компании с большими запасами и продажами товаров в кредит. Нормальное значение коэффициента: 1,5 <= CR <= 2,5',
            'description_long_en' => 'The current ratio is mainly used to give an idea of the company\'s ability to pay back its liabilities (debt and accounts payable) with its assets (cash, marketable securities, inventory, accounts receivable). As such, current ratio can be used to take a rough measurement of a company’s financial health. The higher the current ratio, the more capable the company is of paying its obligations, as it has a larger proportion of asset value relative to the value of its liabilities.Acceptable current ratios vary from industry to industry and are generally between 1.5 and 2 for healthy businesses.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '2-1.png',
            'carousel_files' => ['2-1.png', '2-2.png'],
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'CA',
                    'name' => 'Оборотные активы',
                    'name_en' => 'Сurrent assets',
                    'description' => 'Оборотные активы',
                    'description_en' => 'Сurrent assets',
                ],
                [
                    'code' => 'CL',
                    'name' => 'Краткосрочные обязательства',
                    'name_en' => 'Сurrent  liabilities',
                    'description' => 'Краткосрочные обязательства',
                    'description_en' => 'Сurrent  liabilities',
                ],
                [
                    'code' => '',
                    'name' => 'Период',
                    'name_en' => 'Period',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_DATESLIDER,
                    'is_filter' => true,
                ],
                [
                    'code' => '',
                    'name' => 'Филиал',
                    'name_en' => 'Branch',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_CHECKBOX,
                    'is_filter' => true,
                ],
            ]
        ],
        [
            'name' => 'Коэффициент оборачиваемости запасов (IT)',
            'name_en' => 'Inventory Turnover (IT)',
            'description' => 'Показатель обновляемости запасов сырья, материлов и готовой продукции',
            'description_en' => 'A measure of the number of times inventory sold or used in a time period',
            'description_long' => 'Коэффициент оборачиваемости запасов (Inventory Turnover) показывает как быстро компания продает запасы и сравнивается показатель с средним значентем по отрасли. Низкий оборот предполагает слабые продажи и, следовательно, избыток запасов. Высокий коэффициент означает, либо большие продажи и/или большие скидки. Скорость с которой компания может продавать запасы является важным показателем эффектвности безнеса. Это также один из компонентов расчета для рентабельности активов (ROA); другой компонент рентабельность. Рентабельность компании зависит от активов это то как быстро создается прибыль от показателя продаж. Таким образом, высокий оборот ничего не значит, если компания получает прибыь от распродаж.',
            'description_long_en' => 'Inventory turnover measures how fast a company is selling inventory and is generally compared against industry averages. A low turnover implies weak sales and, therefore, excess inventory. A high ratio implies either strong sales and/or large discounts. The speed with which a company can sell inventory is a critical measure of business performance. It is also one component of the calculation for return on assets (ROA); the other component is profitability. The return a company makes on its assets is a function of how fast it sells inventory at a profit. As such, high turnover means nothing unless the company is making a profit on each sale.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '3-1.png',
            'carousel_files' => ['3-1.png', '3-2.png'],
            'category_name' => BiCategoriesLoadCommand::WAREHOUSE_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Cебестоимость реализованной продукции',
                    'name_en' => 'Cost of Goods Sold',
                    'description' => 'Cебестоимость реализованной продукции',
                    'description_en' => 'Cost of Goods Sold',
                ],
                [
                    'code' => '',
                    'name' => 'Cредняя стоимость запасов',
                    'name_en' => 'Average Inventory',
                    'description' => 'Cредняя стоимость запасов',
                    'description_en' => 'Average Inventory',
                ],
                [
                    'code' => '',
                    'name' => 'Продукт',
                    'name_en' => 'Product',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_MULTISELECT,
                    'is_filter' => true,
                ],
                [
                    'code' => '',
                    'name' => 'Период',
                    'name_en' => 'Period',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_DATESLIDER,
                    'is_filter' => true,
                ],
            ]
        ],
        [
            'name' => 'Прибыль',
            'name_en' => 'Profit',
            'description' => 'Совокупный доход от деятельности компании или предприятия за вычетом совокупных издержек',
            'description_en' => 'Total revenue from the activity of the company or enterprise minus total costs',
            'description_long' => 'Прибыль — это важнейший качественный показатель эффективности деятельности организации, характеризующий рациональность использования средств производства, материальных, трудовых и финансовых ресурсов. Различие между бухгалтерским и экономическим подходом к издержкам обуславливает и различные концепции прибыли. Прибыль организации складывается из трех основных элементов: прибыль (или убыток) от реализации продукции, работ и услуг; прибыль (или убыток) от прочей реализации; операционные, внереализационные и чрезвычайные доходы и расходы. Основную часть получаемой прибыли составляет прибыль от реализации продукции, работ, услуг.',
            'description_long_en' => 'Profit - is the most important quality indicator of the efficiency of the organization, describing the rationale use of the means of production, material, manpower and financial resources. The difference between the accounting and economic approach to the causes and costs of different income concepts. Profit organization made up of three main elements: the profit (or loss) from the sale of goods, works and services; profit (or loss) from other sales; operating, non-operating and extraordinary income and expenses. The main part of the profits of the profit from sales of products, works and services.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '4-1.png',
            'carousel_files' => ['4-1.png', '4-2.png', '4-3.png', '4-4.png'],
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
//                    [
//                        'code' => '',
//                        'name' => '',
//                        'name_en' => '',
//                        'description' => '',
//                        'description_en' => '',
//                    ],
            ]
        ],
        [
            'name' => 'Индекс выполнения стоимости (CPI)',
            'name_en' => 'Cost Performance Index (CPI)',
            'description' => 'Показатель эффективности финансовых затрат проекта на текущий момент',
            'description_en' => 'A measure of the cost efficiency of budgeted resources',
            'description_long' => 'Индекс выполнения стоимости (CPI) - это относительный показатель, характеризующий, насколько больше/меньше потратили по сравнению с тем, сколько должны были потратить на выполнение уже завершенных задач. Подсчет значения показателя позволяет перейти от абсолютных показателей к относительным. Коэффициент применяется для сравнения различных проектов между собой, построения индикаторных диаграмм (светофоров и тп.), прогнозирования результатов проекта, а также для отслеживания финансовой эффективности проекта в динамике (по фазам или ключевым датам). В случае, если индекс выполнения стоимости меньше единицы, то существует риск не уложиться в бюджет проекта.',
            'description_long_en' => 'The Cost Performance Index helps you analyze the efficiency of the cost utilized by the project. It measures the value of the work completed compared to the actual cost spent on the project. The Cost Performance Index specifies how much you are earning for each dollar spent on the project. The Cost Performance Index is an indication of how well the project is remaining on budget.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '5-1.png',
            'carousel_files' => ['5-1.png', '5-2.png'],
            'category_name' => BiCategoriesLoadCommand::PROJECT_MANAGEMENT_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'EA',
                    'name' => 'Освоенный объем',
                    'name_en' => 'Earned Value',
                    'description' => 'Освоенный объем (Earned Value) - выполненна часть работ от запланированного объема',
                    'description_en' => 'Earned Value (EV) - made of the work of the planned volume',
                ],
                [
                    'code' => 'AC',
                    'name' => 'Фактическая стоимость',
                    'name_en' => 'Actual Cost',
                    'description' => 'Фактическая стоимость (Actual Cost) - реальная стоимость выполненных работ',
                    'description_en' => 'Actual Cost (AC) - the actual cost of work performed',
                ],
                [
                    'code' => '',
                    'name' => 'Проект',
                    'name_en' => 'Project',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_CHECKBOX,
                    'is_filter' => true,
                ],
                [
                    'code' => '',
                    'name' => 'Стадии',
                    'name_en' => 'Stages',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_RADIOBUTTON,
                    'is_filter' => true,
                ],
            ]
        ],
        [
            'name' => 'Коэффициент увольнений или потерь персонала',
            'name_en' => 'Ratio of staff layoffs or losses',
            'description' => 'Коэффициент текучести кадров',
            'description_en' => 'Turnover ratio',
            'description_long' => 'Коэффициент текучести кадров - это отношение числа уволенных работников предприятия, выбывших за определенный период по причинам текучести (по собственному желанию, за прогулы, за нарушение техники безопасности, самовольный уход и т.п.), к среднему количеству сотрудников за тот же промежуток времени. Измеряется раз в год или в квартал. Коэффициент отражает неоправданное движение рабочей силы, вызывающее потери рабочего времени на подготовку новых рабочих, освоение ими оборудования и т.д. Естественная текучесть (3-5% в год) способствует своевременному обновлению коллектива и не требует особых мер со стороны руководства и кадровой службы.  Излишняя же текучесть вызывает значительные экономические потери, а также создает организационные, кадровые, технологические, психологические трудности.',
            'description_long_en' => 'Turnover ratio - the ratio of the number of laid-off employees, retired for a certain period for yield reasons (on their own, for absenteeism, for violation of safety, unauthorized maintenance, etc.), to the average number of employees over the same period of time. Measured annually or quarterly. Factor represents an unjustified movement of labor, causing loss of working time in the training of new workers, the development of equipment, etc. Natural flow (3-5% per year) contributes to the timely updating of the team and does not require any action on the part of management and personnel services. Excessive same fluidity causes significant economic losses, and also creates organizational, human, technological, psychological difficulties.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '6-1.png',
            'carousel_files' => ['6-1.png', '6-2.png'],
            'category_name' => BiCategoriesLoadCommand::PERSONNEL_MANAGEMENT_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Число увольнений',
                    'name_en' => 'The number of layoffs',
                    'description' => 'Число увольнений',
                    'description_en' => 'The number of layoffs',
                ],
                [
                    'code' => '',
                    'name' => 'Среднесписочная численность персонала',
                    'name_en' => 'Average number of personnel',
                    'description' => 'Среднесписочная численность персонала',
                    'description_en' => 'Average number of personnel',
                ],
                // TODO: make filters
                [
                    // it's a filter
                    'code' => '',
                    'name' => 'Цеха',
                    'name_en' => 'Divisions',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_RADIOBUTTON,
                ],
                [
                    // it's a filter
                    'code' => '',
                    'name' => 'Года',
                    'name_en' => 'Years',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_RADIOBUTTON
                ],

            ]
        ],
        [
            'name' => 'Численность сотрудников',
            'name_en' => 'Staff headcount',
            'description' => 'Показывает численность сотрудников по отделам и по всей компании',
            'description_en' => 'Shows the number of employees by department and throughout the company',
            'description_long' => 'Данный показатель отражает численность сотрудников по отделам и по всей компании. Карточка позволяет проанализировать изменение численности сотрудников во времени в компании. Для анализа рекомендуется использовать среднесписочную численность сотрудников, которая равняется среднему числу сотрудников за период. Подробный анализ численности сотрудников по отделам компании позволит оптимизировать структуру предприятия.',
            'description_long_en' => 'This indicator reflects the number of employees by department and throughout the company. The card allows you to analyze changes in the number of employees in the company over time. For the analysis it is recommended to use the average number of employees, which is equal to the average number of employees during the period. A detailed analysis of the number of employees by department of the company will optimize the structure of the enterprise.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '7-1.png',
            'carousel_files' => ['7-1.png', '7-2.png', '7-3.png'],
            'category_name' => BiCategoriesLoadCommand::PERSONNEL_MANAGEMENT_CATEGORY_NAME,
            'arguments' => [
            ]
        ],
        [
            'name' => 'Коэффициент рентабельности инвестиций (ROI)',
            'name_en' => 'Return on Investment (ROI)',
            'description' => 'Финансовый показатель, характеризующий доходность инвестиционных вложений',
            'description_en' => 'The financial indicator of the profitability of investments',
            'description_long' => 'Коэффициент рентабельности инвестиций (ROI) - является популярным финнсовым показателем для оценки последствий инвестиций и действиями. На самом деле, несколько различных показателей можн отнести к данному определению, но наимболее известным является ROI. ROI сравнивает прибыль к стоимости и определяет как соотношение денежных ивестиций и расходов инвестирования. По определению, коэффициент ROI рассчитывается как чистая прибыль от инвестиций деленная на сумму инвестиционыых затрат. ',
            'description_long_en' => 'Return on Investment (ROI) - a financial measure of the profitability of investments. 1) ratio is used to evaluate the effectiveness of an investment or to compare the effectiveness of a number of different investments. ROI measures the size of return on investment in relation to the value of the invested summy.Rentabelnost investment is a very popular metric because of its versatility and simplicity. 2) This figure may be used as an initial assessment of the investment project profitability. ROI is very easily calculated and interpreted and can be applied to a wide range of investment types.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '8-1.png',
            'carousel_files' => ['8-1.png', '8-2.png'],
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Прибыль от инвестиций',
                    'name_en' => 'Profit on investment',
                    'description' => 'Прибыль от инвестиций',
                    'description_en' => 'Profit on investment',
                ],
                [
                    'code' => '',
                    'name' => 'Величина инвестиций',
                    'name_en' => 'The value of investments',
                    'description' => 'Величина инвестиций',
                    'description_en' => 'The value of investments',
                ],
                [
                    'code' => '',
                    'name' => 'Период',
                    'name_en' => 'Period',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_DATESLIDER,
                    'is_filter' => true,
                ],
                [
                    'code' => '',
                    'name' => 'Проект',
                    'name_en' => 'Project',
                    'description' => '',
                    'description_en' => '',
                    'ct' => self::CONTROL_TYPE_RADIOBUTTON,
                    'is_filter' => true,
                ],
            ]
        ],
        [
            'name' => 'Прибыль на акцию (EPS)',
            'name_en' => 'Earnings per share (EPS) ',
            'description' => 'Показывает часть прибыли компании, которая приходится на одну акцию',
            'description_en' => 'It shows part of the company\'s profit that is attributable to one share',
            'description_long' => 'Прибыль на акцию (EPS)  показатель отображающий сколько чистого дохода было заработано на одну обыкновенную акцию. Рассчитывается путем деления чистой прибыли за вычетом дивидендов по прилегированным акциям к количеству обыкновенных акций, находящихся в обращении в течение периода. Прибыль на акцию, как правило, считается единственным наиболее важым параметром при определении цены на акцию. ',
            'description_long_en' => 'Earnings per share (EPS) - Earnings per share (EPS) ratio measures how net income have been earned by each share of common stock. It is computed by dividing net income less preferred dividend by the number of shares of common stock outstanding during the period.  It is a popular measure of overall profitability of the company. Earnings per share is generally considered to be the single most important variable in determining a share\'s price.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '9-1.png',
            'carousel_files' => ['9-1.png', '9-2.png'],
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Чистая прибыль',
                    'name_en' => 'Net profit',
                    'description' => 'Чистая прибыль',
                    'description_en' => 'Net profit',
                ],
                [
                    'code' => '',
                    'name' => 'Дивиденды по привилегированным акциям',
                    'name_en' => 'Dividends on preferred shares',
                    'description' => 'Дивиденды по привилегированным акциям',
                    'description_en' => 'Dividends on preferred shares',
                ],
                [
                    'code' => '',
                    'name' => 'Cреднегодовое количество обыкновенных акций в обращении',
                    'name_en' => 'Annual average number of ordinary shares outstanding',
                    'description' => 'Cреднегодовое количество обыкновенных акций в обращении',
                    'description_en' => 'Annual average number of ordinary shares outstanding',
                ],
            ]
        ],
        [
            'name' => 'Воронка продаж',
            'name_en' => 'Sales funnel',
            'description' => 'Принцип распределения клиентов по стадиям процесса продаж от первого контакта до заключения сделки',
            'description_en' => 'The principle of distribution customers in stages of the sales process from the first contact until the conclusion of the transaction',
            'description_long' => 'Воронка продаж (sales funnel) - иллюстрирует идею, что каждая продажа начинается с большого количества потенциальных клиентов и заканчивается с гораздо меньшим количеством людей, которые на самом деле заключают сделку. Число уровней воронки продаж зависит от компании, но как правило, воронкка продаж делится на четыре части - те, кто знает о компании, те, кто имел контакт с компанией, те, кто повторил контакт с компаниемй и те, кто совершил покупку.',
            'description_long_en' => 'Funnel sales (sales funnel) - the principle of customer distribution stages of the sales process from the first contact until the conclusion of the transaction. Sales Funnel is almost universal sales management tool and allows you to solve a wide range of tasks: to identify "bottlenecks" in the sales process, to predict the level of sales in the future, create a sales chart and so on. In marketing sales funnel is actively used for a variety of planning: the required number of contacts in each of the phases of the sale, the required number of promotional materials. Analyzing the sales funnel, you can draw conclusions about the quality of management and the need to intensify efforts on some of the sales stages. If the final number of buyers is not large enough, you need to take action to attract more potential buyers. In the event that one of the phases is a significant sales unduly narrowing funnel sales - means low quality with a potential buyer in the previous step. The number of levels in the funnel of sales depends on the structure of the sales process, industry and produced the product / service.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '10-1.png',
            'carousel_files' => ['10-1.png', '10-2.png'],
            'category_name' => BiCategoriesLoadCommand::ADVERTISING_MARKETING_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => '',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
        [
            'name' => 'Рентабельность инвестиций в маркетинг (ROMI)',
            'name_en' => 'Return on Marketing Investment (ROMI)',
            'description' => 'Мера эффективности маркетинговых инвестиций',
            'description_en' => 'Measure the effectiveness of marketing investments',
            'description_long' => 'Рентабельность инвестиций в маркетинг (ROMI) является показателем, используемым для оценки общей эффективности маркетинговой кампании, чтобы помочь маркетологам принимать более обоснованные решения о распределении будущих инвестиций. ROMI обычно используется в интернет-маркетинге, печатнst и социальные средства массовой информации могут также использовать. ROMI является подмножеством ROI (возврат инвестиций).',
            'description_long_en' => 'Return on marketing investment (ROMI) is a metric used to measure the overall effectiveness of a marketing campaign to help marketers make better decisions about allocating future investments. ROMI is usually used in online marketing, though integrated campaigns that span print, broadcast and social media may also rely on it for determining overall success. ROMI is a subset of ROI (return on investment). ',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '11-1.png',
            'carousel_files' => ['11-1.png', '11-2.png'],
            'category_name' => BiCategoriesLoadCommand::ADVERTISING_MARKETING_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'IR',
                    'name' => 'Прибыль в результате рекламной кампании',
                    'name_en' => 'Profits as a result of the advertising campaign',
                    'description' => 'Прибыль от продажи товаров в рамках рекламной компании',
                    'description_en' => 'Profit from the sale of goods within the framework of an advertising campaign',
                ],
                [
                    'code' => 'C',
                    'name' => 'Расходы на рекламную кампанию',
                    'name_en' => 'The cost of an advertising campaign',
                    'description' => 'Затраты на проведение рекламной компании',
                    'description_en' => 'The cost of an advertising campaign',
                ],
                [
                    'code' => '',
                    'name' => 'Канал распространения информации',
                    'name_en' => 'Channel information dissemination',
                    'description' => 'Канал распространения информации',
                    'description_en' => 'Channel information dissemination',
                ],
                [
                    'code' => '',
                    'name' => 'Маркетинговая компания',
                    'name_en' => 'Marketing company',
                    'description' => 'Маркетинговая кампания',
                    'description_en' => 'Marketing company',
                ],
                [
                    'code' => '',
                    'name' => 'Товарная категория',
                    'name_en' => 'Product categories',
                    'description' => 'Товарная категория',
                    'description_en' => 'Product categories',
                ],
            ]
        ],
        [
            'name' => 'Остатки на складке более n дней',
            'name_en' => 'Remnants of stock for n days',
            'description' => 'Показывает количество остатков находящихся на складе более 15 дней',
            'description_en' => 'It shows the number of residues in warehouse n days',
            'description_long' => 'Показывает количество остатков находящихся на складе более n дней. Приложение позволяет посмотреть сколько товаров находится на складе больше n дней, и принимать решения относительно этих запасов',
            'description_long_en' => 'It shows the number of residues located in the warehouse more than n days. Widget allows you to see how many items are in stock over n days, and make decisions on these stocks',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '12-1.png',
            'carousel_files' => ['12-1.png', '12-2.png'],
            'category_name' => BiCategoriesLoadCommand::WAREHOUSE_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Товар',
                    'name_en' => 'Product',
                    'description' => 'Наименование товара, продукции или материала для изготовления, который храниться на складе',
                    'description_en' => 'Product name, product or material for which stored in a warehouse',
                ],
                [
                    'code' => '',
                    'name' => 'Количество остатка',
                    'name_en' => 'Number of residue',
                    'description' => 'Количество остатков находящихся на складе',
                    'description_en' => 'The number of residues are in stock',
                ],
                [
                    'code' => '',
                    'name' => 'Количество дней хранения',
                    'name_en' => 'Number of storage days',
                    'description' => 'Количество дней хранения товара на складе',
                    'description_en' => 'Number of days to keep goods in stock',
                ],
                [
                    'code' => 'N',
                    'name' => 'Число дней',
                    'name_en' => 'number of days',
                    'description' => 'Это число, которое определяет как долго должен храниться товар чтобы по этим остаткам была приведена информация',
                    'description_en' => 'A number that specifies how long items to be stored in these balances was for information',
                ],
            ]
        ],
        [
            'name' => 'Экономическая эффективность рекламной компании',
            'name_en' => 'The cost-effectiveness of an advertising campaign',
            'description' => 'Экономический эффект рекламирования',
            'description_en' => 'The economic impact of advertising',
            'description_long' => 'Экономический результат, полученный в результате проведения рекламной кампании. Финансовые результаты рекламного мероприятия могут быть равны затратам на его проведение, могут превышать их (прибыльное мероприятие) или быть меньше (убыточно).',
            'description_long_en' => 'The economic result obtained as a result of the advertising campaign. Financial results of the promotion may be equal to the cost of the review, may exceed their (profitable activity) or less than (a loss).',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '13-1.png',
            'carousel_files' => ['13-1.png', '13-2.png'],
            'category_name' => BiCategoriesLoadCommand::ADVERTISING_MARKETING_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'Q',
                    'name' => 'Дополнительный товарооборот',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => 'M',
                    'name' => 'Торговая надбавка на товар, % к цене реализации',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => 'CA',
                    'name' => 'Расходы на рекламу, в денежных единицах',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => 'CQ',
                    'name' => 'Дополнительные расходы по приросту товарооборота, в денежных единицах',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
        [
            'name' => 'Коэффициент удержания клиентов (CRR)',
            'name_en' => 'Customer Retention Rate (CRR)',
            'description' => 'Отражает способность сохранять клиентов',
            'description_en' => 'It reflects the ability to retain customers',
            'description_long' => 'Коэффициент удержания клиентов — это показатель, позволяющий получить представление о доле клиентов, которые остаются или совершают повторяющиеся покупки. Коэффициент показывает уровень сохранения клиентов. Если коэффициент удержания высокий, то можно предположить что уровень удовлетворенности клиентов тоже высокий. Если значение коэффициента низкое, то необходимо выяснить причины неудовлетворенности клиентов.',
            'description_long_en' => 'Customer retention ratio - a measure that allows customers to get an idea of the proportion of which remain, or make repetitive purchases. The ratio indicates the level of customer retention. If the retention rate is high, it can be assumed that the level of customer satisfaction is also high. If the ratio is low, it is necessary to find out the reasons of customer dissatisfaction.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '14-1.png',
            'carousel_files' => ['14-1.png', '14-2.png'],
            'category_name' => BiCategoriesLoadCommand::ADVERTISING_MARKETING_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'E',
                    'name' => 'Количество клиентов на конец периода',
                    'name_en' => 'The number of customers at end of period',
                    'description' => 'Количество клиентов на конец периода',
                    'description_en' => 'The number of customers at end of period',
                ],
                [
                    'code' => 'N',
                    'name' => 'Количество новых клиентов, приобретенных за период',
                    'name_en' => 'The number of new customers acquired during the period',
                    'description' => 'Количество новых клиентов, приобретенных за период',
                    'description_en' => 'The number of new customers acquired during the period',
                ],
                [
                    'code' => 'S',
                    'name' => 'Количество клиентов на начало периода',
                    'name_en' => 'The number of customers at start of period',
                    'description' => 'Количество клиентов на начало периода',
                    'description_en' => 'The number of customers at start of period',
                ],
            ]
        ],
        [
            'name' => 'Коэффициент оттока клиентов (CR)',
            'name_en' => 'Churn rate (CR)',
            'description' => 'Показатель для определения процента клиентов отказавшихся от услуг компании',
            'description_en' => 'Indicator to determine the percentage of clients have waived the company\'s services',
            'description_long' => 'Коэффициент оттока клиентов (Customer Churn Rate) - это процентное отношение количества клиентов, отказавшихся от услуг компании в течение периода к общему количеству клиентов на начало периода. Для компании  количество новых клиентов должно превышать скорость оттока. Коэффициент оттока обычно выражается в процентах.',
            'description_long_en' => 'The churn rate, also known as the rate of attrition, is the percentage of subscribers to a service who discontinue their subscriptions to that service within a given time period. For a company to expand its clientele, its growth rate, as measured by the number of new customers, must exceed its churn rate. The rate is generally expressed as a percentage.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '15-1.png',
            'carousel_files' => ['15-1.png', '15-2.png'],
            'category_name' => BiCategoriesLoadCommand::ADVERTISING_MARKETING_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'NCL',
                    'name' => 'Количество ушедших клиентов за определенный период времени',
                    'name_en' => 'Customer lost during period',
                    'description' => 'Количество клиентов пересташих пользоваться услугами за определенный период времени',
                    'description_en' => 'The number of customers to stop using the services over a given period',
                ],
                [
                    'code' => 'CL',
                    'name' => 'Количество клиентов на начало периода',
                    'name_en' => 'Customer at beginning of period',
                    'description' => 'Количество клиентов пользующихся услугами на начало периода',
                    'description_en' => 'The number of customers using the services at the beginning of the period',
                ],
                [
                    'code' => '',
                    'name' => 'Наименование товара/услуги',
                    'name_en' => 'Name of product / service',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
        [
            'name' => 'Индекс выполнения сроков (SPI)',
            'name_en' => 'Schedule performance index (SPI)',
            'description' => 'Измерение достигнутых объемов выполнения проекта по сравнению с запланированным объемом',
            'description_en' => 'Measurement of volumes to achieve the project compared with the planned volume of',
            'description_long' => 'Показатель, характеризующий, насколько мы больше/меньше сделали по сравнению объемом задач, запланированным на текущую дату в расписании проекта. Применяется для сравнения различных проектов. Значение SPI (Schedule performance index) меньше 1,0 указывает на то, что выполнено меньше работ, чем было запланировано.',
            'description_long_en' => 'A measure of the how we are more / less than did the volume of tasks scheduled for the current date in the project schedule. It is used to compare between different projects.A value less than 1 indicates that less work was actually performed than was scheduled.',
            'author' => "Эттон",
            'author_en' => "Etton",
//            'image_file' => '',
//            'carousel_files' => [],
            'category_name' => BiCategoriesLoadCommand::PROJECT_MANAGEMENT_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => 'EV',
                    'name' => 'Освоенный объем',
                    'name_en' => 'Earned Value',
                    'description' => 'Выполненная часть работ от запланированного объема',
                    'description_en' => 'Made of the work of the planned volume',
                ],
                [
                    'code' => 'PV',
                    'name' => 'Плановый объем',
                    'name_en' => 'Planned Value',
                    'description' => 'Объем запланированных работ',
                    'description_en' => 'The amount of the planned works',
                ],
                [
                    'code' => '',
                    'name' => 'Дата выполнения работ',
                    'name_en' => 'Date of execution of works',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Фильтр по проектам',
                    'name_en' => 'Project filter',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Этапы работ',
                    'name_en' => 'Work stages',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
        /*      [
                  'name' => 'Карта покупок',
                  'name_en' => 'Buying on the map',
                  'description' => 'Представление информации о поупках по регионам на карте',
                  'description_en' => 'Reporting on purchases by region on the map',
                  'description_long' => 'Карточка позволяет отобразить информацию о покупках на карте. Дополнительные подключаемые данные позволяют осуществить разнообразную аналитику. Настройка вида карты предоставляет разнообразные виды представления аналитической информации.',
                  'description_long_en' => 'The widget allows you to display information about the purchases on the map. Additional connecting sources allow us to implement a variety of analytics. Setting the map view provides a variety of presentation of analytical information.',
                  'author' => "Эттон",
                  'author_en' => "Etton",
                  'image_file' => '17-1.png',
                  'carousel_files' => ['17-1.png'],
                  'category_name' => BiCategoriesLoadCommand::ADVERTISING_MARKETING_CATEGORY_NAME,
                  'arguments' => [
                      [
                          'code' => '',
                          'name' => 'Регион',
                          'name_en' => 'Region',
                          'description' => '',
                          'description_en' => '',
                      ],
                      [
                          'code' => '',
                          'name' => 'Количество проданных товаров',
                          'name_en' => 'Number of items sold',
                          'description' => '',
                          'description_en' => '',
                      ],
                      [
                          'code' => '',
                          'name' => 'Категории товаров',
                          'name_en' => 'Categories of goods',
                          'description' => '',
                          'description_en' => '',
                      ],
                      [
                          'code' => '',
                          'name' => 'Время продажи товара',
                          'name_en' => 'Time sale of goods',
                          'description' => '',
                          'description_en' => '',
                      ],
                  ]
              ],*/
        [
            'name' => 'Карта продаж',
            'name_en' => 'Sale on map',
            'description' => 'Представление информации об объеме продаж по регионам на карте',
            'description_en' => 'Reporting on sales by region on the map',
            'description_long' => 'Карточка позволяет отобразить информацию об объемах продажах компании на карте. Дополнительные подключаемые данные позволяют осуществить разнообразную аналитику. Настройка вида карты предоставляет разнообразные виды представления аналитической информации.',
            'description_long_en' => 'The widget allows you to display information about the company\'s sales on the map. Additional connecting sources allow us to implement a variety of analytics. Setting the map view provides a variety of presentation of analytical information.',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '18-1.png',
            'carousel_files' => ['18-1.png'],
            'category_name' => BiCategoriesLoadCommand::ADVERTISING_MARKETING_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Регион',
                    'name_en' => 'Region',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Количество проданных товаров',
                    'name_en' => 'Number of items sold',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Категории товаров',
                    'name_en' => 'Categories of goods',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Время продажи товара',
                    'name_en' => 'Time sale of goods',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
        /* [
             'name' => 'Курс валют',
             'name_en' => 'Exchange Rate',
             'description' => 'Цена денежной единицы страны, выраженная в денежной единице другой страны',
             'description_en' => 'The price currency of one country expressed in the currency of another country',
             'description_long' => 'Курс валюты – это цена денежной единицы одной страны относительно денежной единицы другой страны. В приложении представлен курс валют по данным сайта Центрального банка Российской Федерации. Имеется возможность построения различных видов графиков и фильтрации по валютам.',
             'description_long_en' => 'Exchange rate - the price of the currency of one country\'s currency relative to other countries. The widget presented to the exchange rate according to the site of the Central Bank of the Russian Federation. The card has the possibility of constructing various types of currencies charts and filtering.',
             'author' => "Эттон",
             'author_en' => "Etton",
             'image_file' => '19-1.png',
             'carousel_files' => ['19-1.png'],
             # TODO: 'category_name' => BiCategoriesLoadCommand,
             'arguments' => [
 //                [
 //                    'code' => '',
 //                    'name' => '',
 //                    'name_en' => '',
 //                    'description' => '',
 //                    'description_en' => '',
 //                ],
             ]
         ],*/
        [
            'name' => 'Выручка',
            'name_en' => 'Revenue',
            'description' => 'Выручка от продажи готовой продукции, товаров',
            'description_en' => 'Revenue from the sale of finished-products, goods',
            'description_long' => 'Доход, получаемый от продажи товаров или услуг, или любое другое капитала или активов, полученных предприятием в результате основной деятельности до вычета расходов. Прибыль обычно показывает верхнюю границу (доход или убыток), из которой получают чистую прибыль путем вычетания всех расходв, издержек.',
            'description_long_en' => 'The income generated from sale of goods or services, or any other use of capital or assets, associated with the main operations of an organization before any costs or expenses are deducted. Revenue is shown usually as the top item in an income (profit and loss) statement from which all charges, costs, and expenses are subtracted to arrive at net income. ',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '4-1.png',
            'carousel_files' => ['4-1.png', '4-2.png', '4-3.png', '4-4.png'],
            'price' => 80,
            'price_en' => 1.15,
            'rating' => 5,
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'representations' => [self::REPRESENTATION_PIE, self::REPRESENTATION_LINE, self::REPRESENTATION_DIAGRAM],
            'arguments' => [
                [
                    'code' => '',
                    'name' => 'Выручка',
                    'name_en' => 'Revenue',
                    'description' => '',
                    'description_en' => '',
                    'dimension' => 'Y',
                ],
                [
                    'code' => '',
                    'name' => 'Организация',
                    'name_en' => 'Organization',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Услуги/Товар',
                    'name_en' => 'Services / Goods',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Контрагент',
                    'name_en' => 'Partner',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Договор',
                    'name_en' => 'Contract',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Проект',
                    'name_en' => 'Project',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Дата',
                    'name_en' => 'Date',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => 'Выручка без НДС',
                    'name_en' => 'Revenue without VAT',
                    'description' => '',
                    'description_en' => '',
                    'dimension' => 'Y',
                ],
            ]
        ],
        /*[
            'name' => 'Space-матрица',
            'name_en' => 'SPACE matrix',
            'description' => 'Матрица стратегического положения и оценки действий',
            'description_en' => 'Matrix strategic position and action evaluation',
            'description_long' => "Матрица стратегического положения и  оценки действий (SPACE-матрица) является инструментом стратегического менеджмента, который фокусируется на определении конкурентных преимуществ организации. SPACE-матрица может быть использована наряду и с другими аналитическими инструментами, таких как SWOT анализ, матрица BCG, отраслевой анализ рынка, матрица IE. SPACE-матрица разбивается на четыре квадранта, где каждый квадрант означает разный тип или характер стратегии:\n-агрессивное положение;\n-конкурентное положение;\n-консервативное положение;\n-оборонительное положение.\nАгрессивное положение - это привлекательная и относительно стабильная среда, компания имеет конкурентное преимущество и может защитить его, угрозы незначительны и проявляются в возможном появлении новых конкурентов в отрасль, механизмы могут быть направлены на новые приобретения, увеличение доли рынка и сосредоточение внимания на новых продуктах.\nКонкурентное положение - это привлекательная и относительно нестабильная среда, компания имеет некоторые конкурентные преимущества, угрозами являются финансовая устойчивость компании - компания должна искать пути их фиксации, решение возможно в присоединении другой организации, повышении эффективности производства и притока наличных денежных средств.\nКонсервативное положение - стабильная среда с низким темпом роста и финасово стабильной компаниии, угрозой является конкурентоспособность продукта, компания должна защищать свои успешные продукты и разрабатывать новые, думать о возможности проникновения в более привлекательную среду и снижать затраты.\nОборонительное положение - непривлекатеьная среда, компания испытывает недостаток в конкурентоспособных продуктах, угрозой является конкурентоспособность, компания должна снижать затраты, сокращать объем инвестиций и рассмотреть вопрос выхода из среды.",
            'description_long_en' => "The Strategic Position & Aсtion Evaluation matrix or short a SPACE matrix is a strategic management tool that focuses on strategy formulation especially as related to the competitive position of an organization. The SPACE matrix can be used as a basis for other analyses, such as the SWOT analysis, BCG matrix model, industry analysis, or assessing strategic alternatives (IE matrix). The SPACE matrix is broken down to four quadrants where each quadrant suggests a different type or a nature of a strategy:\nAggressive\nConservative\nDefensive\nCompetitive\nAggressive position - an attractive and relatively stable industry, the company has a competitive advantage and it can protect it, a critical factor is the possible entry of new competitors into the industry, it may be considered new acquisitions, increasing market share and focusing on competitive products\nCompetitive position - attractive and relatively unstable environment, the company has some competitive advantage, a critical factor is the company’s financial strength - the company should look for ways of their attachment, the solution is the possibility of joining another company, increasing production efficiency and strengthening cash flow\nConservative position - a stable industry with low growth rate and financially stable company, a critical factor is in the product competitiveness, company should protect its succesfull products and develop new ones and think about the possibilities of the penetration into the industry more attractive and reduce costs.\nDefensive position - an unattractive industry, the company lacks competitive products and financial resources, a critical factor is the competitiveness, the company should reduce costs, reduce investment and consider leaving the industry.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '20-1.png',
            'carousel_files' => ['20-1.png'],
            'arguments' => [
                [
                    'code' => 'FS',
                    'name' => 'Финансовый потенциал',
                    'name_en' => 'Financial Strength',
                    'description' => 'Финасовое положение фирмы',
                    'description_en' => 'Company\'s financial situation',
                ],
                [
                    'code' => 'IS',
                    'name' => 'Промышлынный потенциал',
                    'name_en' => 'Industry Strength',
                    'description' => 'Привлекательность (сила) рассматриваемой отрасли',
                    'description_en' => 'Attractiveness of the considered industry',
                ],
                [
                    'code' => 'ES',
                    'name' => 'Стабильность обстановки',
                    'name_en' => 'Environmental Stability',
                    'description' => 'Степень стабильности внешней среды',
                    'description_en' => 'The degree of stability of the environment',
                ],
                [
                    'code' => 'CA',
                    'name' => 'Конкурентные преимущества',
                    'name_en' => 'Competitive Advantage',
                    'description' => 'Конкурентные преимущества',
                    'description_en' => 'Competitive advantages',
                ],
            ]
        ],*/
        /*[
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => "Эттон",
            'author_en' => "Etton",
            'image_file' => '',
            'carousel_files' => [''],
            'arguments' => [
                [
                    'code' => '',
                    'name' => '',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ]*/
    ];
}
