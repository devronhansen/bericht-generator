<?php

use App\Classes\DocumentFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var DocumentFactory
     */
    public $object;
    private $demands;
    /**
     * @var \Mockery\Mock|\PhpOffice\PhpWord\TemplateProcessor
     */
    private $templateProcessor;

    public function setUp()
    {
        parent::setUp();
        $this->templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(app_path().'/template.docx');
        $this->object = new DocumentFactory($this->templateProcessor);
        $this->createAndLoginUser();
        $this->demands = $this->createDemands();
    }

    public function tearDown()
    {
        $this->object = null;
    }

    public function test_it_creates_Documents_from_array()
    {
        $this->object->createDocumentsWith($this->demands);

        $this->assertFileExists(public_path() . "/files/BerichtNr" . $this->demands['nr'] . ".docx");
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

}