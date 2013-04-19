<?php
App::uses('Router', 'Routing');
App::uses('MarkdownHelper', 'Yamd.View/Helper');
App::uses('Controller', 'Controller');
App::uses('View', 'View');

class YamdTestController extends Controller {
    public $name = 'Yamd';
    public $uses = null;
    public $helpers = array('Yamd.Markdown');
}

class MarkdownHelperTest extends CakeTestCase {

    public function setUp() {
        parent::setUp();
        $controller = new Controller;
        $View = new View($controller);
        $options = array('markdownFilePath' => TMP . 'tests' . DS);
        $this->Markdown = new MarkdownHelper($View, $options);
        Configure::write('Config.language', 'en-us');
    }

    public function tearDown() {
        parent::tearDown();
        unset($this->Markdown);
    }

    /**
     * testHtmlize
     *
     * jpn: 通常の文字列のhtml化
     */
    public function testHtmlize(){
        $md = '# Yamd: Yet Anothor Markdown plugin for CakePHP' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '## Requirement' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '[PHP Markdown or PHP Markdown Extra](http://michelf.ca/projects/php-markdown/)' . PHP_EOL;

        $result = $this->Markdown->htmlize($md);

        $this->assertContains('<h1>Yamd: Yet Anothor Markdown plugin for CakePHP</h1>', $result);
    }

    /**
     * testLoadFile
     *
     * jpn: mdファイルをhtml化して出力
     */
    public function testLoadFile(){
        $md = '# Yamd: Yet Anothor Markdown plugin for CakePHP' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '## Requirement' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '[PHP Markdown or PHP Markdown Extra](http://michelf.ca/projects/php-markdown/)' . PHP_EOL;

        file_put_contents(TMP . 'tests' . DS . 'test.md', $md);

        $result = $this->Markdown->loadFile('test');

        $this->assertContains('<h1>Yamd: Yet Anothor Markdown plugin for CakePHP</h1>', $result);
    }

    /**
     * testLoadFileWithScript
     *
     * jpn: mdファイルをhtml化して出力
     */
    public function testLoadFileWithScript(){
        $md = '# <?php echo "Yamd: Yet Anothor Markdown plugin for CakePHP"; ?>' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '## Requirement' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '<?php echo $this->Html->image("cake.png"); ?>' . PHP_EOL;

        file_put_contents(TMP . 'tests' . DS . 'test.md', $md);

        $result = $this->Markdown->loadFile('test');

        $this->assertContains('<h1>Yamd: Yet Anothor Markdown plugin for CakePHP</h1>', $result);

        $this->assertContains('<img src="', $result);
    }

    /**
     * testLoadLocaleFile
     *
     * jpn: Config.languageの値を見てM17Nを実現
     */
    public function testLoadLocaleFile(){
        $md = '# Yamd: Yet Anothor Markdown plugin for CakePHP' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '## Requirement' . PHP_EOL;
        $md .= '' . PHP_EOL;
        $md .= '[PHP Markdown or PHP Markdown Extra](http://michelf.ca/projects/php-markdown/)' . PHP_EOL;
        file_put_contents(TMP . 'tests' . DS . 'test.md', $md);

        $obj = new Folder(TMP . 'tests' . DS . 'jpn', true, 0777);

        $jpnMd = '# ヤムド: Yet Anothor Markdown plugin for CakePHP' . PHP_EOL;
        $jpnMd .= '' . PHP_EOL;
        $jpnMd .= '## 依存ライブラリ' . PHP_EOL;
        $jpnMd .= '' . PHP_EOL;
        $jpnMd .= '[PHP Markdown or PHP Markdown Extra](http://michelf.ca/projects/php-markdown/)' . PHP_EOL;
        file_put_contents(TMP . 'tests' . DS . 'jpn' . DS . 'test.md', $jpnMd);

        $result = $this->Markdown->loadFile('test');
        $this->assertContains('<h1>Yamd: Yet Anothor Markdown plugin for CakePHP</h1>', $result);

        Configure::write('Config.language', 'ja');
        $result = $this->Markdown->loadFile('test');
        $this->assertContains('<h1>ヤムド: Yet Anothor Markdown plugin for CakePHP</h1>', $result);
    }
}