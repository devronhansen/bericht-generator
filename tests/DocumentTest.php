<?php

use App\Classes\DocumentFactory;

class DocumentTest extends TestCase
{
    public function testCanUSeeTheRightThing()
    {
        $this->visit('/')
            ->see('Bericht generieren');
    }

    /*public function test_it_creates_Documents_And_Zips()
    {
        $faker = Faker\Factory::create();
        $startWeek = $faker->date($format = 'd.m.Y', $max = 'now');
        $endWeek = $faker->date($format = 'd.m.Y', $max = $startWeek);

        $demands = [
            "start" => $startWeek,
            "end" => $endWeek,
            "nr" => 1,
            "year" => $faker->numberBetween(1, 3)
        ];

        $documentFactory = new DocumentFactory();
        $documentFactory->createDocumentsWith($demands)
            ->getZipFromDocuments();


        $this->assertTrue(file_exists("./public/files/BerichtNr1.docx"));
    }*/

    public function test_it_deletes_Documents_After_Zipping()
    {
        $faker = Faker\Factory::create();
        $startWeek = $faker->date($format = 'd.m.Y', $max = 'now');
        $endWeek = $faker->date($format = 'd.m.Y', $max = $startWeek);

        $demands = [
            "start" => $startWeek,
            "end" => $endWeek,
            "nr" => $faker->numberBetween(1, 60),
            "year" => $faker->numberBetween(1, 3)
        ];

        $documentFactory = new DocumentFactory();
        $documentFactory->createDocumentsWith($demands)
            ->getZipFromDocuments()
            ->deleteDocuments('true');

        $this->assertTrue(!file_exists("BerichtNr" . $demands['nr'] . ".docx"));
    }
}