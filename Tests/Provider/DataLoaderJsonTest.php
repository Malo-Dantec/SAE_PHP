<?php
declare(strict_types=1);

use Classes\Config\Database;
use PHPUnit\Framework\TestCase;
use Classes\Provider\DataLoaderJson;
use Classes\Model\Restaurant;
use PDO;
use PDOStatement;
use function PHPUnit\Framework\assertEquals;

class DataLoaderJsonTest extends TestCase {
    private string $testFilePath;
    private PDO $mockDb;

    protected function setUp(): void {
        $this->db = Database::getConnection();
        $this->testFilePath = __DIR__."/../Data/test.json";
    }


    public function testFileNotFoundThrowsException(): void {
        $this->expectException(\Exception::class);
        new DataLoaderJson(__DIR__ . '/fichier_inexistant.json');
    }

    public function testGetData(): void {
        $dataLoader = new DataLoaderJson($this->testFilePath);
        $data = $dataLoader->getData();

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertSame("Cha+", $data[0]["name"]);
    }

    public function testJsonToData(): void {
       
        // Mock de DataLoaderJson
        $dataLoader = new DataLoaderJson($this->testFilePath);

        // Tester la conversion JSON -> Base de données
        $this->assertTrue($dataLoader->jsonToData($this->db));
        $restau= Restaurant::getById(383);
        $this->assertEquals($restau["nomRestau"], "Cha+");
        Restaurant::rmRestau(383);
        $this->db->exec("DELETE FROM sqlite_sequence WHERE name='RESTAURANT'");
    }
}
?>
