<?php

use App\Classes\DocumentFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Classes\ZipFactory;

class DocumentTest extends TestCase
{
    use DatabaseTransactions;

    private $documentFactory;
    private $demands;
    private $zipFactory;

    public function setUp() {
        parent::setUp();
        $this->documentFactory = new DocumentFactory();
        $this->zipFactory = new ZipFactory();
        $this->createAndLoginUser();
        $this->demands = $this->createDemands();
    }

    public function tearDown() {
        $this->documentFactory = null;
    }

    public function test_it_creates_Documents_from_array()
    {
        $docfac = new DocumentFactory();

        $docfac->createDocumentsWith($this->demands);

        $this->assertFileExists(public_path()."\\files\\BerichtNr" . $this->demands['nr'] . ".docx");
    }

    private function createDemands()
    {
        $faker = Faker\Factory::create();
        $startWeek = $faker->date($format = 'd.m.Y', $max = 'now');
        $endWeek = $faker->date($format = 'd.m.Y', $max = $startWeek);

        return $demands = [
            "start" => $startWeek,
            "end" => $endWeek,
            "nr" => $faker->numberBetween(1, 60),
            "year" => $faker->numberBetween(1, 3)
        ];
    }

    private function createAndLoginUser()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user);
    }

   /* public function test_Create_Zip_From_Documents()
    {
        $documents = $this->documentFactory->createDocumentsWith($this->demands);

        $zipFactory = new ZipFactory();
        $zipFactory->createZipFromDocuments($documents);

        $this->assertFileExists(public_path()."\\result.zip");
    }*/

}