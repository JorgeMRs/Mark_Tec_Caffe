use PHPUnit\Framework\TestCase;

class DbConnectTest extends TestCase
{
    public function testDbConnection()
    {
        // Arrange
        $expectedHost = 'localhost';
        $expectedUser = 'root';
        $expectedPassword = 'password';
        $expectedDatabase = 'my_database';

        // Act
        $actualConnection = getDbConnection();

        // Assert
        $this->assertInstanceOf(mysqli::class, $actualConnection);
        $this->assertEquals($expectedHost, $actualConnection->host_info);
        $this->assertEquals($expectedUser, $actualConnection->username);
        $this->assertEquals($expectedPassword, $actualConnection->password);
        $this->assertEquals($expectedDatabase, $actualConnection->database);
    }
}use PHPUnit\Framework\TestCase;

class DbConnectTest extends TestCase
{
    public function testDbConnection()
    {
        $mysqli = getDbConnection();

        $this->assertInstanceOf(mysqli::class, $mysqli);
    }

    public function testDbConnectionError()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error de conexión a la base de datos');

        // Set invalid database credentials
        $_ENV['DB_HOST'] = 'localhost';
        $_ENV['DB_USER'] = 'root';
        $_ENV['DB_PASS'] = 'password';
        $_ENV['DB_NAME'] = 'non_existing_database';

        getDbConnection();
    }
}use PHPUnit\Framework\TestCase;

class DbConnectTest extends TestCase
{
    public function testDbConnection()
    {
        // Arrange
        $expectedHost = 'localhost';
        $expectedUser = 'root';
        $expectedPassword = 'password';
        $expectedDatabase = 'mydatabase';

        // Act
        $actualConnection = getDbConnection();

        // Assert
        $this->assertInstanceOf(mysqli::class, $actualConnection);
        $this->assertEquals($expectedHost, $actualConnection->host_info);
        $this->assertEquals($expectedUser, $actualConnection->username);
        $this->assertEquals($expectedPassword, $actualConnection->password);
        $this->assertEquals($expectedDatabase, $actualConnection->database);
    }
}