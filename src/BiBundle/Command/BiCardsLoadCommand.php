<?php

namespace BiBundle\Command;

use BiBundle\Entity\Argument;
use BiBundle\Entity\Card;
use BiBundle\Entity\CardCarouselImage;
use BiBundle\Entity\CardCategory;
use BiBundle\Entity\File;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BiCardsLoadCommand extends ContainerAwareCommand
{
    private $uploadPath;

    private $cardImagesPath = '/images/cards/';

    /** @var  EntityManager */
    private $entityManager;

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

        $this->uploadPath = rtrim($this->getContainer()->getParameter('upload_dir'), '/');
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
        $file = new File();
        $file->setPath($this->cardImagesPath . $filename);
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
        $path = $this->uploadPath . $this->cardImagesPath . $filename;
        if (!file_exists($path)) {
            throw new \ErrorException("Image file '$filename' not found");
        } elseif (!is_file($path)) {
            throw new \ErrorException("'$filename' is not a file");
        }
    }

    private $data = [
        [
            'name' => "Выручка",
            'name_en' => "Revenue",
            'description' => "Выручка от продажи готовой продукции, товаров",
            'description_en' => "Revenue from the sale of finished-products, goods",
            'description_long' => "Доход, получаемый от продажи товаров или услуг, или любое другое капитала или активов, полученных предприятием в результате основной деятельности до вычета расходов. Прибыль обычно показывает верхнюю границу (доход или убыток), из которой получают чистую прибыль путем вычетания всех расходв, издержек.",
            'description_long_en' => "The income generated from sale of goods or services, or any other use of capital or assets, associated with the main operations of an organization before any costs or expenses are deducted. Revenue is shown usually as the top item in an income (profit and loss) statement from which all charges, costs, and expenses are subtracted to arrive at net income.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'price' => 80,
            'price_en' => 1.15,
            'rating' => 5,
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => '"Выручка"',
                    'name_en' => '',
                    'dimension' => 'Y',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Выручка без НДС"',
                    'name_en' => '',
                    'dimension' => 'Y',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Услуги/Товар"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Контрагент"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Договор"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Проект"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Дата"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Организация"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
            ]
        ],
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
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => '"Организация"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Сумма задолженности"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Контрагент"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Дата"',
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
            'category_name' => BiCategoriesLoadCommand::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'arguments' => [
                [
                    'code' => '',
                    'name' => '"Организация"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Сумма задолженности"',
                    'name_en' => '',
                    'dimension' => 'Y',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Контрагент"',
                    'name_en' => '',
                    'description' => '',
                    'description_en' => '',
                ],
                [
                    'code' => '',
                    'name' => '"Дата"',
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
            'author' => "Зорин В.",
            'author_en' => "Zorin V.",
            'image_file' => '1-1.png',
            'carousel_files' => ['1-1.png', '1-2.png'],
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
            'author' => 'Зорин В.',
            'author_en' => 'Zorin V.',
            'image_file' => '2-1.png',
            'carousel_files' => ['2-1.png', '2-2.png'],
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
            ]
        ],
        [
            'name' => 'Коэффициент оборачиваемости запасов (IT)',
            'name_en' => 'Inventory Turnover (IT)',
            'description' => 'Показатель обновляемости запасов сырья, материлов и готовой продукции',
            'description_en' => 'A measure of the number of times inventory sold or used in a time period',
            'description_long' => 'Коэффициент оборачиваемости запасов (Inventory Turnover) показывает как быстро компания продает запасы и сравнивается показатель с средним значентем по отрасли. Низкий оборот предполагает слабые продажи и, следовательно, избыток запасов. Высокий коэффициент означает, либо большие продажи и/или большие скидки. Скорость с которой компания может продавать запасы является важным показателем эффектвности безнеса. Это также один из компонентов расчета для рентабельности активов (ROA); другой компонент рентабельность. Рентабельность компании зависит от активов это то как быстро создается прибыль от показателя продаж. Таким образом, высокий оборот ничего не значит, если компания получает прибыь от распродаж.',
            'description_long_en' => 'Inventory turnover measures how fast a company is selling inventory and is generally compared against industry averages. A low turnover implies weak sales and, therefore, excess inventory. A high ratio implies either strong sales and/or large discounts. The speed with which a company can sell inventory is a critical measure of business performance. It is also one component of the calculation for return on assets (ROA); the other component is profitability. The return a company makes on its assets is a function of how fast it sells inventory at a profit. As such, high turnover means nothing unless the company is making a profit on each sale.',
            'author' => 'Мирзануров Д.',
            'author_en' => 'Mirzanurov D.',
            'image_file' => '3-1.png',
            'carousel_files' => ['3-1.png', '3-2.png'],
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
            ]
        ],
        [
            'name' => 'Прибыль',
            'name_en' => 'Profit',
            'description' => 'Совокупный доход от деятельности компании или предприятия за вычетом совокупных издержек',
            'description_en' => 'Total revenue from the activity of the company or enterprise minus total costs',
            'description_long' => 'Прибыль — это важнейший качественный показатель эффективности деятельности организации, характеризующий рациональность использования средств производства, материальных, трудовых и финансовых ресурсов. Различие между бухгалтерским и экономическим подходом к издержкам обуславливает и различные концепции прибыли. Прибыль организации складывается из трех основных элементов: прибыль (или убыток) от реализации продукции, работ и услуг; прибыль (или убыток) от прочей реализации; операционные, внереализационные и чрезвычайные доходы и расходы. Основную часть получаемой прибыли составляет прибыль от реализации продукции, работ, услуг.',
            'description_long_en' => 'Profit - is the most important quality indicator of the efficiency of the organization, describing the rationale use of the means of production, material, manpower and financial resources. The difference between the accounting and economic approach to the causes and costs of different income concepts. Profit organization made up of three main elements: the profit (or loss) from the sale of goods, works and services; profit (or loss) from other sales; operating, non-operating and extraordinary income and expenses. The main part of the profits of the profit from sales of products, works and services.',
            'author' => 'Мирзануров Д.',
            'author_en' => 'Mirzanurov D.',
            'image_file' => '4-1.png',
            'carousel_files' => ['4-1.png', '4-2.png', '4-3.png', '4-4.png'],
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
            'description_long_en' => '"The Cost Performance Index helps you analyze the efficiency of the cost utilized by the project. It measures the value of the work completed compared to the actual cost spent on the project. The Cost Performance Index specifies how much you are earning for each dollar spent on the project. The Cost Performance Index is an indication of how well the project is remaining on budget."',
            'author' => 'Зорин В.',
            'author_en' => 'Zorin V.',
            'image_file' => '5-1.png',
            'carousel_files' => ['5-1.png', '5-2.png'],
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
            ]
        ],
        [
            'name' => 'Коэффициент увольнений или потерь персонала',
            'name_en' => 'Ratio of staff layoffs or losses',
            'description' => 'Коэффициент текучести кадров',
            'description_en' => 'Turnover ratio',
            'description_long' => 'Коэффициент текучести кадров - это отношение числа уволенных работников предприятия, выбывших за определенный период по причинам текучести (по собственному желанию, за прогулы, за нарушение техники безопасности, самовольный уход и т.п.), к среднему количеству сотрудников за тот же промежуток времени. Измеряется раз в год или в квартал. Коэффициент отражает неоправданное движение рабочей силы, вызывающее потери рабочего времени на подготовку новых рабочих, освоение ими оборудования и т.д. Естественная текучесть (3-5% в год) способствует своевременному обновлению коллектива и не требует особых мер со стороны руководства и кадровой службы.  Излишняя же текучесть вызывает значительные экономические потери, а также создает организационные, кадровые, технологические, психологические трудности.',
            'description_long_en' => 'Turnover ratio - the ratio of the number of laid-off employees, retired for a certain period for yield reasons (on their own, for absenteeism, for violation of safety, unauthorized maintenance, etc.), to the average number of employees over the same period of time. Measured annually or quarterly. Factor represents an unjustified movement of labor, causing loss of working time in the training of new workers, the development of equipment, etc. Natural flow (3-5% per year) contributes to the timely updating of the team and does not require any action on the part of management and personnel services. Excessive same fluidity causes significant economic losses, and also creates organizational, human, technological, psychological difficulties.',
            'author' => 'Ягафарова З.',
            'author_en' => 'Yagafarova Z.',
            'image_file' => '6-1.png',
            'carousel_files' => ['6-1.png', '6-2.png'],
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
            ]
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ],
        [
            'name' => '',
            'name_en' => '',
            'description' => '',
            'description_en' => '',
            'description_long' => '',
            'description_long_en' => '',
            'author' => '',
            'author_en' => '',
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
        ]

    ];
}
