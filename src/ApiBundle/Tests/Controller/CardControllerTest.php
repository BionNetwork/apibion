<?php

namespace ApiBundle\Tests\Controller;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardCategory;
use BiBundle\Repository\CardRepository;
use BiBundle\Service\CardCategoryService;
use BiBundle\Service\CardService;

class CardControllerTest extends ControllerTestCase
{
    /**
     * @var  CardCategoryService
     */
    private $cardCategoryService;

    /**
     * @var  CardService
     */
    private $cardService;

    /** @var  CardRepository */
    private $cardRepository;

    /** @var  string */
    private $webDirectory;

    /** @var  CardRepository */
    private $cardRepository;

    /**
     * @inheritdoc
     */
    protected $apiTokenAuthentication = true;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $container = self::$kernel->getContainer();
        $this->cardCategoryService = $container->get('bi.card_category.service');
        $this->cardService = $container->get('bi.card.service');
        $this->cardRepository = $container->get('repository.card_repository');
        $this->webDirectory = rtrim($container)->getParameter('web_root_directory'), '/');
    }

    /**
     * Tests getCardsCategoriesAction
     */
    public function testGetCardsCategories()
    {
        /** @var CardCategory[] $categories */
        $categories = $this->cardCategoryService->getByFilter(
            new \BiBundle\Entity\Filter\CardCategory()
        );

        $this->client->request('GET', "/api/v1/cards/categories");
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertCount(count($categories), $data['data']);

    }

    /**
     * Tests getCardsCategorizedAction
     */
    public function testGetCardsCategorized()
    {
        /** @var Card[] $cards */
        $cards = $this->cardService->getAllCards();

        $this->client->request('GET', "/api/v1/cards/categorized");
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals(count($cards) > 0, count($data['data']) > 0);
    }

    public function testGetCardAction()
    {
        /** @var Card $card */
        $card = $this->cardRepository->findOneBy([]);
        $this->client->request('GET', "/api/v1/cards/{$card->getId()}");

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertSame($card->getId(), $data['data']['id']);
    }

    public function testGetCardsAction()
    {
        $this->client->request('GET', "/api/v1/cards");

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertSame(count($this->cardRepository->findAll()), count($data['data']));
    }

    public function testCardImage()
    {
        /** @var Card $card */
        $card = $this->cardRepository->createQueryBuilder('c')
            ->where('c.imageFile IS NOT NULL')
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult();

        $this->client->request('GET', "/api/v1/cards", ['id' => $card->getId()]);
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertSame(1, count($data['data']));
        $this->assertArrayHasKey('image', $data['data'][0]);
        $this->assertFileExists($this->webDirectory . $data['data'][0]['image']);
    }

    public function testCardCarousel()
    {
        /** @var Card $card */
        $card = $this->cardRepository->findOneBy([]);

        $this->client->request('GET', "/api/v1/cards", ['id' => $card->getId()]);
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $data);
        $this->assertSame(1, count($data['data']));
        $this->assertArrayHasKey('carousel', $data['data'][0]);
        $this->assertInternalType('array', $data['data'][0]['carousel']);
        $this->assertTrue(count($data['data'][0]['carousel']) > 0);
        foreach ($data['data'][0]['carousel'] as $fileWebPath) {
            $this->assertFileExists($this->webDirectory . $fileWebPath);
        }
    }
}
