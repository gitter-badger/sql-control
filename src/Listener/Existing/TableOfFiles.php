<?php
namespace Ctimt\SqlControl\Listener\Existing;

use Ctimt\SqlControl\Config\Configuration;
use Ctimt\SqlControl\Enum\Attributes;
use Ctimt\SqlControl\Enum\Events;
use Ctimt\SqlControl\Enum\Messages;
use Ctimt\SqlControl\Enum\Statements;
use Ctimt\SqlControl\Framework\SqlChangeFactory;
use Ctimt\SqlControl\Framework\SqlControlManager;
use Ctimt\SqlControl\Parser\FileGroup;
use Ctimt\SqlControl\Parser\FileVersion;
use Ctimt\SqlControl\Parser\NullParser;
use Ctimt\SqlControl\Visitor\VisitorInterface;
use PDO;
use PDOException;
use Zend\EventManager\Event;

/**
 * Description of Table
 *
 * @author David Schoenbauer <dschoenbauer@gmail.com>
 */
class TableOfFiles implements VisitorInterface
{

    use \Ctimt\SqlControl\Listener\SetupTrait;

    private $_adapter;
    private $_table;
    private $_fieldScriptName;
    private $_fieldSuccess;
    private $_config;

    public function __construct(\PDO $adapter, Configuration $config, $table = 'VersionController', $fieldScriptName = 'VersionController_script', $fieldSuccess = 'VersionController_success')
    {
        $this
            ->setAdapter($adapter)
            ->setTable($table)
            ->setFieldScriptName($fieldScriptName)
            ->setFieldSuccess($fieldSuccess)
            ->setConfig($config);
    }

    public function visitSqlControlManager(SqlControlManager $sqlControlManager)
    {
        $sqlControlManager->getEventManager()->attach(Events::LOAD, [$this, 'onLoad']);
        $sqlControlManager->getEventManager()->attach(Events::RESULT, [$this, 'onResult']);
    }

    public function onLoad(Event $event)
    {
        /* @var $sqlControlManager SqlControlManager */
        $sqlControlManager = $event->getTarget();
        $this->getAdapter()->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $rows = $this->getRows($event->getTarget());

        $appliedSql = $sqlControlManager->getAttributes()->getValue(Attributes::APPLIED_SQL_VERSIONS, []);
        $factory = new SqlChangeFactory(new FileVersion(), new FileGroup(), new NullParser([]));
        foreach ($rows as $row) {
            $appliedSql[$row] = $factory->getSqlChange($row, './');
        }
        $event->getTarget()->getEventManager()->trigger(Events::LOG_INFO, $event->getTarget(), ['message' => Messages::INFO_VERSIONS_IDENTIFIED, 'context' => ['count' => count($appliedSql)]]);
        $sqlControlManager->getAttributes()->add(Attributes::APPLIED_SQL_VERSIONS, $appliedSql);
    }

    public function onResult(Event $event)
    {
        /* @var $scm SqlControlManager */
        $scm = $event->getTarget();
        $file = $event->getParam('file');
        $success = (bool) $event->getParam('success') ? 1 : 0;
        if ($file !== null) {
            $sql = sprintf("INSERT INTO %s (%s, %s) VALUES(:file, :success)", $this->getTable(), $this->getFieldScriptName(), $this->getFieldSuccess());
            $stmt = $scm->getAdapter()->prepare($sql);
            $stmt->execute(compact('file', 'success'));
        }
    }

    protected function getData(PDO $adapter, $sql)
    {
        return $adapter->query($sql)->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getSelectSql()
    {
        return sprintf($this->getConfig()->getValue(Statements::GET_EXECUTED_CHANGES), $this->getFieldScriptName(), $this->getTable(), $this->getFieldSuccess());
    }

    /**
     * @return \PDO
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    public function getFieldScriptName()
    {
        return $this->_fieldScriptName;
    }

    public function getFieldSuccess()
    {
        return $this->_fieldSuccess;
    }

    public function getTable()
    {
        return $this->_table;
    }

    public function setAdapter($adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }

    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }

    public function setFieldScriptName($fieldScriptName)
    {
        $this->_fieldScriptName = $fieldScriptName;
        return $this;
    }

    public function setFieldSuccess($fieldSuccess)
    {
        $this->_fieldSuccess = $fieldSuccess;
        return $this;
    }

    public function getRows(SqlControlManager $sqlControlManager)
    {
        try {
            return $this->getData($this->getAdapter(), $this->getSelectSql());
        } catch (PDOException $exc) {
            $this->setup($exc, $sqlControlManager);
            return [];
        }
    }
    
    /**
     * 
     * @return Configuration 
     */
    public function getConfig() {
        return $this->_config;
    }

    public function setConfig(Configuration $config) {
        $this->_config = $config;
        return $this;
    }
    
}
