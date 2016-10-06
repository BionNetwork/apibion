<?php

namespace ApiBundle\Tests\Controller;

use BiBundle\Entity\Card;
use BiBundle\Entity\CardCategory;
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
        $this->cardCategoryService = self::$kernel->getContainer()->get('bi.card_category.service');
        $this->cardService = self::$kernel->getContainer()->get('bi.card.service');
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
}
