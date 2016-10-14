<?php


namespace ApiBundle\Tests\Controller;

use BiBundle\Entity\Activation;
use BiBundle\Entity\Card;
use BiBundle\Entity\Purchase;


/**
 * @property \BiBundle\Entity\User user
 * @property \BiBundle\Service\TestEntityFactory factory
 * @property \BiBundle\Repository\CardRepository cardRepository
 * @property \BiBundle\Repository\PurchaseRepository purchaseRepository
 * @property \BiBundle\Repository\ActivationRepository activationRepository
 */
class PurchaseControllerTest extends ControllerTestCase
{
    protected $apiTokenAuthentication = true;

    public static function setUpBeforeClass()
    {
        self::bootKernel();
        self::$kernel->getContainer()->get('bi.test_entity.factory')->purgeTestEntitiesClass(Purchase::class);
    }

    public static function tearDownAfterClass()
    {
        self::bootKernel();
        self::$kernel->getContainer()->get('bi.test_entity.factory')->purgeTestEntitiesClass(Purchase::class);
    }

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();
        $container = self::$kernel->getContainer();
        $this->cardRepository = $container->get('repository.card_repository');
        $this->purchaseRepository = $container->get('repository.purchase_repository');
        $this->activationRepository = $container->get('repository.activation_repository');
        $this->factory = $container->get('bi.test_entity.factory');
        $this->user = $this->getUser();
    }

    public function testGetPurchases()
    {
        $purchase = $this->factory->createPurchase($this->user);

        $this->client->request('GET', "/api/v1/purchases");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $responseJson = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('data', $responseJson);
        $this->assertCount(1, $responseJson['data']);

        $purchaseData = array_pop($responseJson['data']);
        $this->assertSame($purchase->getId(), $purchaseData['id']);
        $this->assertArrayHasKey('card', $purchaseData);
        $this->assertSame($purchase->getCard()->getId(), $purchaseData['card']['id']);
        $this->assertArrayHasKey('carousel', $purchaseData['card']);
    }

    public function testPostPurchase()
    {
        /** @var Card $card */
        $card = $this->cardRepository->findOneBy([]);

        $this->client->request('POST', "/api/v1/purchases", ['card' => $card->getId()]);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $responseJson = json_decode($this->client->getResponse()->getContent(), true);
        $purchase = $this->purchaseRepository->find($responseJson['data']['id']);
        $this->assertInstanceOf(Purchase::class, $purchase);

        return $purchase;
    }

    /**
     * @depends testPostPurchase
     */
    public function testPurchaseActivation(Purchase $purchase)
    {
        $this->client->request('POST', "/api/v1/purchases/{$purchase->getId()}/activations");
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $responseJson = json_decode($this->client->getResponse()->getContent(), true);
        $activation = $this->activationRepository->find($responseJson['data']['id']);
        $this->assertInstanceOf(Activation::class, $activation);
    }
}
