<?php
namespace AdministracaoTest\Controller;

use Administracao\Controller\UsuarioController;
use Administracao\Model\UsuarioTable;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Administracao\Model\Usuario;
use Prophecy\Argument;

class UsuarioControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected $usuarioTable;

    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(UsuarioTable::class, $this->mockUsuarioTable()->reveal());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];

        return $config;
    }

    protected function mockUsuarioTable()
    {
        $this->usuarioTable = $this->prophesize(UsuarioTable::class);

        return $this->usuarioTable;
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->usuarioTable->fetchAll()->willReturn([]);

        $this->dispatch('/album', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Usuario');
        $this->assertControllerName(UsuarioController::class);
        $this->assertControllerClass('UsuarioController');
        $this->assertMatchedRouteName('album');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $this->usuarioTable
            ->saveUsuario(Argument::type(Usuario::class))
            ->shouldBeCalled();

        $postData = [
            'title'  => 'Led Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id'     => '',
        ];
        $this->dispatch('/album/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album');
    }
}
