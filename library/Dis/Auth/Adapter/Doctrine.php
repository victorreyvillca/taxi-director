<?php

class Dist_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface {

    /**
     * Doctrine EntityManager
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $_entityManager;

    /**
     * The entity name to check for an identity.
     *
     * @var string
     */
    protected $entityName;

    /**
     * Field to be used as identity.
     *
     * @var string
     */
    protected $identityField;

    /**
     * The field to be used as credential.
     *
     * @var string
     */
    protected $credentialField;

    /**
     * @var
     */
    protected $entity;

    /**
     * Constructor sets configuration options.
     *
     * @param  Doctrine\ORM\EntiyManager
     * @param  string
     * @param  string
     * @param  string
     * @return void
     */
    public function __construct($em, $entityName = null, $identityField = null, $credentialField = null)
    {
        $this->_entityManager = $em;

        if (null !== $entityName) {
            $this->setEntityName($entityName);
        }

        if (null !== $identityField) {
            $this->setIdentityField($identityField);
        }

        if (null !== $credentialField) {
            $this->setCredentialField($credentialField);
        }
    }

    /**
     * Set entity name.
     *
     * @param  string
     * @return App_Auth_Adapter_Doctrine
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * Set identity field.
     *
     * @param  string
     * @return App_Auth_Adapter_Doctrine
     */
    public function setIdentityField($identityField)
    {
        $this->identityField = $identityField;
        return $this;
    }

    /**
     * Set credential field.
     *
     * @param  string
     * @return App_Auth_Adapter_Doctrine
     */
    public function setCredentialField($credentialField)
    {
        $this->credentialField = $credentialField;
        return $this;
    }

    /**
     * Set the value to be used as identity.
     *
     * @param  string
     * @return App_Auth_Adapter_Doctrine
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * Set the value to be used as credential.
     *
     * @param  string
     * @return App_Auth_Adapter_Doctrine
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
        return $this;
    }

    /**
     * Defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {

    }

    /**
     * This method abstracts the steps involved with
     * making sure that this adapter was indeed setup properly with all
     * required pieces of information.
     *
     * @throws Zend_Auth_Adapter_Exception - in the event that setup was not done properly
     */
    protected function _authenticateSetup()
    {
        $exception = null;
        if (null !== $exception) {
            /**
             * @see Zend_Auth_Adapter_Exception
             */
            throw new Zend_Auth_Adapter_Exception($exception);
        }
    }

    /**
     * Construct the Doctrine query.
     *
     * @return Doctrine\ORM\Query
     */
    protected function _getQuery()
    {
        $qb = $this->_entityManager->createQueryBuilder()
            ->select('e')
            ->from($this->entityName, 'e')
            ->where(' e.ttEnd is null and e.' . $this->identityField . " = '".$this->identity."'");
		//$qb->setParameter(1, $this->identity);
        return $qb->getQuery();
    }

	/**
     * getResultRowObject() - Returns the result row as a stdClass object
     *
     * @param  string|array $returnColumns
     * @param  string|array $omitColumns
     * @return stdClass|boolean
     */
    public function getResultRowObject($returnColumns = null, $omitColumns = null)
    {
        if (!$this->entity) {
            return false;
        }

        $returnObject = new stdClass();

        if (null !== $returnColumns) {

            $availableColumns = array_keys($this->entity);
            foreach ( (array) $returnColumns as $returnColumn) {
                if (in_array($returnColumn, $availableColumns)) {
                    $returnObject->{$returnColumn} = $this->entity[$returnColumn];
                }
            }
            return $returnObject;

        } elseif ($omitColumns !== null) {
            $omitColumns = (array) $omitColumns;
            $cols = $this->_entityManager->getClassMetadata(get_class($this->entity))->getColumnNames();
			$arrayEntity = array();
			foreach($cols as $col){
				if (!in_array($col, $omitColumns)) {
					$getter = 'get'.ucfirst($col);
					$returnObject->{$col} = $this->entity->$getter();
				}
			}
			return $returnObject;

        } else {

            foreach ($this->entity as $resultColumn => $resultValue) {
                $returnObject->{$resultColumn} = $resultValue;
            }
            return $returnObject;

        }
    }
}
