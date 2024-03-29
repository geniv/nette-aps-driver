<?php declare(strict_types=1);

use Dibi\Connection;
use Dibi\Drivers\NoDataResult;
use Dibi\IDataSource;

/**
 * Class ApsDriver
 *
 * @author  geniv
 * @noinspection PhpUnused
 */
class ApsDriver
{
    /** @var Connection */
    private $connection;


    /**
     * ApsDriver constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    //  ___       _                        _                  _   _               _
    // |_ _|_ __ | |_ ___ _ __ _ __   __ _| |  _ __ ___   ___| |_| |__   ___   __| |
    //  | || '_ \| __/ _ \ '__| '_ \ / _` | | | '_ ` _ \ / _ \ __| '_ \ / _ \ / _` |
    //  | || | | | ||  __/ |  | | | | (_| | | | | | | | |  __/ |_| | | | (_) | (_| |
    // |___|_| |_|\__\___|_|  |_| |_|\__,_|_| |_| |_| |_|\___|\__|_| |_|\___/ \__,_|
    //

    /**
     * Load internal mssql connector
     *
     * @return Connection
     * @noinspection PhpUnused
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * Load table promenne
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getPromenne(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('Promenne');
    }

    //   ____
    //  |  _ \ ___ _ __ ___  ___  _ __
    //  | |_) / _ \ '__/ __|/ _ \| '_ \
    //  |  __/  __/ |  \__ \ (_) | | | |
    //  |_|   \___|_|  |___/\___/|_| |_|
    //

    /**
     * Get list person.
     * VIEW Osoby
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListPerson(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_Person')->as('person');
    }


    /**
     * Save person.
     * STORED PROCEDURE: Uložení / editace osoby
     *
     * @param int|null      $idPerson osoby; 0 = vytvoření nové osoby / HODNOTA = editace stávající osoby
     * @param int|null      $idFolder reference [api_Folder].[IDFolder]: ID složky, do které bude osoba přiřazena
     * @param string|null   $firstName křestní jméno
     * @param string|null   $middleName prostřední jméno
     * @param string|null   $lastName příjmení
     * @param string|null   $title titul
     * @param string|null   $pin PIN – pro aplikace s identifikací čtečka + PIN
     * @param string|null   $workspace pracoviště
     * @param string|null   $job funkce
     * @param string|null   $personalNumber osobní číslo
     * @param string|null   $phone telefon
     * @param string|null   $cellPhone mobilní telefon
     * @param string|null   $email email
     * @param int|null      $externalKey1 vyhrazeno pro klíč do externí databáze
     * @param int|null      $externalKey2 vyhrazeno pro klíč do externí databáze
     * @param DateTime|null $validityOrigin počátek platnosti oprávnění
     * @param DateTime|null $validityExpiration konec platnosti oprávnění
     * @return bool
     * @noinspection PhpUnused
     */
    public function savePerson(
        int      $idPerson = null,
        int      $idFolder = null,
        string   $firstName = null,
        string   $middleName = null,
        string   $lastName = null,
        string   $title = null,
        string   $pin = null,
        string   $workplace = null,
        string   $job = null,
        string   $personalNumber = null,
        string   $phone = null,
        string   $cellPhone = null,
        string   $email = null,
        int      $externalKey1 = null,
        int      $externalKey2 = null,
        DateTime $validityOrigin = null,
        DateTime $validityExpiration = null
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_SavePerson %s', [
                $idPerson, $idFolder, $firstName, $middleName, $lastName, $title, $pin, $workplace,
                $job, $personalNumber, $phone, $cellPhone, $email, $externalKey1, $externalKey2,
                $validityOrigin ? $validityOrigin->format('Y-m-d H:i:s') : null,
                $validityExpiration ? $validityExpiration->format('Y-m-d H:i:s') : null
            ])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Delete person.
     * STORED PROCEDURE: Mazání osoby
     *
     * @param int $idPerson ID osoby
     * @return bool
     * @noinspection PhpUnused
     */
    public function deletePerson(int $idPerson): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_DeletePerson %s', [$idPerson])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //    ____              _
    //   / ___|__ _ _ __ __| |
    //  | |   / _` | '__/ _` |
    //  | |___ (_| | | | (_| |
    //   \____\__,_|_|  \__,_|
    //

    /**
     * Get list card.
     * VIEW: Karty
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListCard(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_Card')->as('card');
    }


    /**
     * Save card.
     * STORED PROCEDURE: Uložení / editace karty
     *
     * @param int|null    $idCard ID karty; 0 = vytvoření nové karty / HODNOTA = editace stávající karty
     * @param int|null    $idPerson reference [api_Person].[IDPerson]: ID osoby, které je karta přiřazena
     * @param string|null $code kód karty; unikátní pro každou kartu
     * @param string|null $description popis karty
     * @param bool|null   $isVisitors příznak návštěvnické karty (0=obyčejná; 1=návštěvnická)
     * @param bool|null   $isOneTimeUse příznak jednorázové karty (0=obyčejná; 1=jednorázová)
     * @return bool
     * @noinspection PhpUnused
     */
    public function saveCard(
        int    $idCard = null,
        int    $idPerson = null,
        string $code = null,
        string $description = null,
        bool   $isVisitors = null,
        bool   $isOneTimeUse = null
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_SaveCard %s', [$idCard, $code, $description, $isVisitors, $isOneTimeUse, $idPerson])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Delete card.
     * STORED PROCEDURE: Mazání karty
     *
     * @param int $idCard ID karty
     * @return bool
     * @noinspection PhpUnused
     */
    public function deleteCard(int $idCard): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_DeleteCard %s', [$idCard])
            ->execute();
        return $result->getRowCount() > 0;
    }


    //   _____     _     _
    //  |  ___|__ | | __| | ___ _ __ 
    //  | |_ / _ \| |/ _` |/ _ \ '__|
    //  |  _| (_) | | (_| |  __/ |   
    //  |_|  \___/|_|\__,_|\___|_|   
    //                               

    /**
     * Get list folder.
     * VIEW: Složky
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListFolder(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_Folder')->as('folder');
    }


    /**
     * SaveFolder.
     * STORED PROCEDURE: Uložení / editace složky
     *
     * @param int|null    $idFolder ID složky; 0 = vytvoření nové složky / HODNOTA = editace stávající složky
     * @param int|null    $parentIdFolder reference [api_Folder].[IDFolder]: ID nadřazené složky
     * @param string|null $name název
     * @return bool
     * @noinspection PhpUnused
     */
    public function saveFolder(
        int    $idFolder = null,
        int    $parentIdFolder = null,
        string $name = null
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_SaveFolder %s', [$idFolder, $parentIdFolder, $name])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Delete folder.
     * STORED PROCEDURE: Mazání složky
     *
     * @param int $idFolder ID složky
     * @return bool
     * @noinspection PhpUnused
     */
    public function deleteFolder(int $idFolder): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_DeleteFolder %s', [$idFolder])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //      _                         ____                       
    //     / \   ___ ___ ___ ___ ___ / ___|_ __ ___  _   _ _ __  
    //    / _ \ / __/ __/ _ \ __/ __| |  _| '__/ _ \| | | | '_ \ 
    //   / ___ \ (__ (__  __\__ \__ \ |_| | | | (_) | |_| | |_) |
    //  /_/   \_\___\___\___|___/___/\____|_|  \___/ \__,_| .__/ 
    //                                                    |_|    

    /**
     * Get list access group.
     * VIEW: Přístupové skupiny
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListAccessGroup(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_AccessGroup')->as('accessGroup');
    }


    /**
     * Save access group.
     * STORED PROCEDURE: Uložení / editace přístupové skupiny
     *
     * @param int|null    $idAccessGroup ID přístupové skupiny
     * @param int|null    $idSystem reference [api_System].[IDSystem]: ID systému, pro který jsou přístupy definovány
     * @param int|null    $number
     * @param int|null    $userNumber
     * @param string|null $name název přístupové skupiny
     * @param bool|null   $accessModule01 přístup na čtečku XX
     * @param bool|null   $accessModule02
     * @param bool|null   $accessModule03
     * @param bool|null   $accessModule04
     * @param bool|null   $accessModule05
     * @param bool|null   $accessModule06
     * @param bool|null   $accessModule07
     * @param bool|null   $accessModule08
     * @param bool|null   $accessModule09
     * @param bool|null   $accessModule10
     * @param bool|null   $accessModule11
     * @param bool|null   $accessModule12
     * @param bool|null   $accessModule13
     * @param bool|null   $accessModule14
     * @param bool|null   $accessModule15
     * @param bool|null   $accessModule16
     * @param bool|null   $accessModule17
     * @param bool|null   $accessModule18
     * @param bool|null   $accessModule19
     * @param bool|null   $accessModule20
     * @param bool|null   $accessModule21
     * @param bool|null   $accessModule22
     * @param bool|null   $accessModule23
     * @param bool|null   $accessModule24
     * @param bool|null   $accessModule25
     * @param bool|null   $accessModule26
     * @param bool|null   $accessModule27
     * @param bool|null   $accessModule28
     * @param bool|null   $accessModule29
     * @param bool|null   $accessModule30
     * @param bool|null   $accessModule31
     * @param bool|null   $accessModule32
     * @param bool|null   $accessModule33
     * @param bool|null   $accessModule34
     * @param bool|null   $accessModule35
     * @param bool|null   $accessModule36
     * @param bool|null   $accessModule37
     * @param bool|null   $accessModule38
     * @param bool|null   $accessModule39
     * @param bool|null   $accessModule40
     * @param bool|null   $accessModule41
     * @param bool|null   $accessModule42
     * @param bool|null   $accessModule43
     * @param bool|null   $accessModule44
     * @param bool|null   $accessModule45
     * @param bool|null   $accessModule46
     * @param bool|null   $accessModule47
     * @param bool|null   $accessModule48
     * @param bool|null   $accessModule49
     * @param bool|null   $accessModule50
     * @param bool|null   $accessModule51
     * @param bool|null   $accessModule52
     * @param bool|null   $accessModule53
     * @param bool|null   $accessModule54
     * @param bool|null   $accessModule55
     * @param bool|null   $accessModule56
     * @param bool|null   $accessModule57
     * @param bool|null   $accessModule58
     * @param bool|null   $accessModule59
     * @param bool|null   $accessModule60
     * @param bool|null   $accessModule61
     * @param bool|null   $accessModule62
     * @param bool|null   $accessModule63
     * @param bool|null   $accessModule64
     * @param int|null    $authorizationModule01 příznak autorizace na čtečce XX
     * @param int|null    $authorizationModule02
     * @param int|null    $authorizationModule03
     * @param int|null    $authorizationModule04
     * @param int|null    $authorizationModule05
     * @param int|null    $authorizationModule06
     * @param int|null    $authorizationModule07
     * @param int|null    $authorizationModule08
     * @param int|null    $authorizationModule09
     * @param int|null    $authorizationModule10
     * @param int|null    $authorizationModule11
     * @param int|null    $authorizationModule12
     * @param int|null    $authorizationModule13
     * @param int|null    $authorizationModule14
     * @param int|null    $authorizationModule15
     * @param int|null    $authorizationModule16
     * @param int|null    $authorizationModule17
     * @param int|null    $authorizationModule18
     * @param int|null    $authorizationModule19
     * @param int|null    $authorizationModule20
     * @param int|null    $authorizationModule21
     * @param int|null    $authorizationModule22
     * @param int|null    $authorizationModule23
     * @param int|null    $authorizationModule24
     * @param int|null    $authorizationModule25
     * @param int|null    $authorizationModule26
     * @param int|null    $authorizationModule27
     * @param int|null    $authorizationModule28
     * @param int|null    $authorizationModule29
     * @param int|null    $authorizationModule30
     * @param int|null    $authorizationModule31
     * @param int|null    $authorizationModule32
     * @param int|null    $authorizationModule33
     * @param int|null    $authorizationModule34
     * @param int|null    $authorizationModule35
     * @param int|null    $authorizationModule36
     * @param int|null    $authorizationModule37
     * @param int|null    $authorizationModule38
     * @param int|null    $authorizationModule39
     * @param int|null    $authorizationModule40
     * @param int|null    $authorizationModule41
     * @param int|null    $authorizationModule42
     * @param int|null    $authorizationModule43
     * @param int|null    $authorizationModule44
     * @param int|null    $authorizationModule45
     * @param int|null    $authorizationModule46
     * @param int|null    $authorizationModule47
     * @param int|null    $authorizationModule48
     * @param int|null    $authorizationModule49
     * @param int|null    $authorizationModule50
     * @param int|null    $authorizationModule51
     * @param int|null    $authorizationModule52
     * @param int|null    $authorizationModule53
     * @param int|null    $authorizationModule54
     * @param int|null    $authorizationModule55
     * @param int|null    $authorizationModule56
     * @param int|null    $authorizationModule57
     * @param int|null    $authorizationModule58
     * @param int|null    $authorizationModule59
     * @param int|null    $authorizationModule60
     * @param int|null    $authorizationModule61
     * @param int|null    $authorizationModule62
     * @param int|null    $authorizationModule63
     * @param int|null    $authorizationModule64
     * @return bool
     * @noinspection PhpUnused
     */
    public function saveAccessGroup(
        int    $idAccessGroup = null,
        int    $idSystem = null,
        int    $number = null,
        int    $userNumber = null,
        string $name = null,
        bool   $accessModule01 = null,
        bool   $accessModule02 = null,
        bool   $accessModule03 = null,
        bool   $accessModule04 = null,
        bool   $accessModule05 = null,
        bool   $accessModule06 = null,
        bool   $accessModule07 = null,
        bool   $accessModule08 = null,
        bool   $accessModule09 = null,
        bool   $accessModule10 = null,
        bool   $accessModule11 = null,
        bool   $accessModule12 = null,
        bool   $accessModule13 = null,
        bool   $accessModule14 = null,
        bool   $accessModule15 = null,
        bool   $accessModule16 = null,
        bool   $accessModule17 = null,
        bool   $accessModule18 = null,
        bool   $accessModule19 = null,
        bool   $accessModule20 = null,
        bool   $accessModule21 = null,
        bool   $accessModule22 = null,
        bool   $accessModule23 = null,
        bool   $accessModule24 = null,
        bool   $accessModule25 = null,
        bool   $accessModule26 = null,
        bool   $accessModule27 = null,
        bool   $accessModule28 = null,
        bool   $accessModule29 = null,
        bool   $accessModule30 = null,
        bool   $accessModule31 = null,
        bool   $accessModule32 = null,
        bool   $accessModule33 = null,
        bool   $accessModule34 = null,
        bool   $accessModule35 = null,
        bool   $accessModule36 = null,
        bool   $accessModule37 = null,
        bool   $accessModule38 = null,
        bool   $accessModule39 = null,
        bool   $accessModule40 = null,
        bool   $accessModule41 = null,
        bool   $accessModule42 = null,
        bool   $accessModule43 = null,
        bool   $accessModule44 = null,
        bool   $accessModule45 = null,
        bool   $accessModule46 = null,
        bool   $accessModule47 = null,
        bool   $accessModule48 = null,
        bool   $accessModule49 = null,
        bool   $accessModule50 = null,
        bool   $accessModule51 = null,
        bool   $accessModule52 = null,
        bool   $accessModule53 = null,
        bool   $accessModule54 = null,
        bool   $accessModule55 = null,
        bool   $accessModule56 = null,
        bool   $accessModule57 = null,
        bool   $accessModule58 = null,
        bool   $accessModule59 = null,
        bool   $accessModule60 = null,
        bool   $accessModule61 = null,
        bool   $accessModule62 = null,
        bool   $accessModule63 = null,
        bool   $accessModule64 = null,
        int    $authorizationModule01 = null,
        int    $authorizationModule02 = null,
        int    $authorizationModule03 = null,
        int    $authorizationModule04 = null,
        int    $authorizationModule05 = null,
        int    $authorizationModule06 = null,
        int    $authorizationModule07 = null,
        int    $authorizationModule08 = null,
        int    $authorizationModule09 = null,
        int    $authorizationModule10 = null,
        int    $authorizationModule11 = null,
        int    $authorizationModule12 = null,
        int    $authorizationModule13 = null,
        int    $authorizationModule14 = null,
        int    $authorizationModule15 = null,
        int    $authorizationModule16 = null,
        int    $authorizationModule17 = null,
        int    $authorizationModule18 = null,
        int    $authorizationModule19 = null,
        int    $authorizationModule20 = null,
        int    $authorizationModule21 = null,
        int    $authorizationModule22 = null,
        int    $authorizationModule23 = null,
        int    $authorizationModule24 = null,
        int    $authorizationModule25 = null,
        int    $authorizationModule26 = null,
        int    $authorizationModule27 = null,
        int    $authorizationModule28 = null,
        int    $authorizationModule29 = null,
        int    $authorizationModule30 = null,
        int    $authorizationModule31 = null,
        int    $authorizationModule32 = null,
        int    $authorizationModule33 = null,
        int    $authorizationModule34 = null,
        int    $authorizationModule35 = null,
        int    $authorizationModule36 = null,
        int    $authorizationModule37 = null,
        int    $authorizationModule38 = null,
        int    $authorizationModule39 = null,
        int    $authorizationModule40 = null,
        int    $authorizationModule41 = null,
        int    $authorizationModule42 = null,
        int    $authorizationModule43 = null,
        int    $authorizationModule44 = null,
        int    $authorizationModule45 = null,
        int    $authorizationModule46 = null,
        int    $authorizationModule47 = null,
        int    $authorizationModule48 = null,
        int    $authorizationModule49 = null,
        int    $authorizationModule50 = null,
        int    $authorizationModule51 = null,
        int    $authorizationModule52 = null,
        int    $authorizationModule53 = null,
        int    $authorizationModule54 = null,
        int    $authorizationModule55 = null,
        int    $authorizationModule56 = null,
        int    $authorizationModule57 = null,
        int    $authorizationModule58 = null,
        int    $authorizationModule59 = null,
        int    $authorizationModule60 = null,
        int    $authorizationModule61 = null,
        int    $authorizationModule62 = null,
        int    $authorizationModule63 = null,
        int    $authorizationModule64 = null
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_SaveAccessGroup %s', [
                $idAccessGroup, $idSystem, $number, $userNumber, $name,
                $accessModule01, $accessModule02, $accessModule03, $accessModule04, $accessModule05,
                $accessModule06, $accessModule07, $accessModule08, $accessModule09, $accessModule10,
                $accessModule11, $accessModule12, $accessModule13, $accessModule14, $accessModule15,
                $accessModule16, $accessModule17, $accessModule18, $accessModule19, $accessModule20,
                $accessModule21, $accessModule22, $accessModule23, $accessModule24, $accessModule25,
                $accessModule26, $accessModule27, $accessModule28, $accessModule29, $accessModule30,
                $accessModule31, $accessModule32, $accessModule33, $accessModule34, $accessModule35,
                $accessModule36, $accessModule37, $accessModule38, $accessModule39, $accessModule40,
                $accessModule41, $accessModule42, $accessModule43, $accessModule44, $accessModule45,
                $accessModule46, $accessModule47, $accessModule48, $accessModule49, $accessModule50,
                $accessModule51, $accessModule52, $accessModule53, $accessModule54, $accessModule55,
                $accessModule56, $accessModule57, $accessModule58, $accessModule59, $accessModule60,
                $accessModule61, $accessModule62, $accessModule63, $accessModule64,
                $authorizationModule01, $authorizationModule02, $authorizationModule03, $authorizationModule04, $authorizationModule05,
                $authorizationModule06, $authorizationModule07, $authorizationModule08, $authorizationModule09, $authorizationModule10,
                $authorizationModule11, $authorizationModule12, $authorizationModule13, $authorizationModule14, $authorizationModule15,
                $authorizationModule16, $authorizationModule17, $authorizationModule18, $authorizationModule19, $authorizationModule20,
                $authorizationModule21, $authorizationModule22, $authorizationModule23, $authorizationModule24, $authorizationModule25,
                $authorizationModule26, $authorizationModule27, $authorizationModule28, $authorizationModule29, $authorizationModule30,
                $authorizationModule31, $authorizationModule32, $authorizationModule33, $authorizationModule34, $authorizationModule35,
                $authorizationModule36, $authorizationModule37, $authorizationModule38, $authorizationModule39, $authorizationModule40,
                $authorizationModule41, $authorizationModule42, $authorizationModule43, $authorizationModule44, $authorizationModule45,
                $authorizationModule46, $authorizationModule47, $authorizationModule48, $authorizationModule49, $authorizationModule50,
                $authorizationModule51, $authorizationModule52, $authorizationModule53, $authorizationModule54, $authorizationModule55,
                $authorizationModule56, $authorizationModule57, $authorizationModule58, $authorizationModule59, $authorizationModule60,
                $authorizationModule61, $authorizationModule62, $authorizationModule63, $authorizationModule64,
            ])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Delete access group.
     * STORED PROCEDURE: Mazání přístupové skupiny
     *
     * @param int $idAccessGroup ID přístupové skupiny
     * @return bool
     * @noinspection PhpUnused
     */
    public function deleteAccessGroup(int $idAccessGroup): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_DeleteAccessGroup %s', [$idAccessGroup])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //   _____                 _   ____        __ _       _ _   _             
    //  | ____|_   _____ _ __ | |_|  _ \  ___ / _(_)_ __ (_) |_(_) ___  _ __  
    //  |  _| \ \ / / _ \ '_ \| __| | | |/ _ \ |_| | '_ \| | __| |/ _ \| '_ \ 
    //  | |___ \ V /  __/ | | | |_| |_| |  __/  _| | | | | | |_| | (_) | | | |
    //  |_____| \_/ \___|_| |_|\__|____/ \___|_| |_|_| |_|_|\__|_|\___/|_| |_|
    //                                                                        

    /**
     * Get list event.
     * VIEW: Událost
     * VIEW: Definice události
     *
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListEvent(): IDataSource
    {
        //chyba v SQL - > melo by byt Description - opraveno aliasem
        return $this->connection->select('e.*, ed.Decription Description')
            ->from('api_Event')->as('e')
            ->join('api_EventDefinition')->as('ed')
            ->on('ed.ID_System=e.ID_System')
            ->and('ed.ID_Module=e.ID_Module')
            ->and('ed.IDEventCode=e.ID_EventCode');
    }


    /**
     * Update event definition.
     * STORED PROCEDURE: Editace definice typu události
     *
     * @param int         $idEventCode ID kódu události
     * @param int         $idModule reference [api_Module].[IDModule]: ID modulu, na kterém je typ události definován
     * @param int         $idSystem reference [api_System].[IDSystem]: ID systému, ke kterému modul patří
     * @param string|null $description textový popis typu události
     * @param string|null $alertText textový popis poplachové zprávy
     * @param int|null    $alertLevel úroveň poplachu
     * @return bool
     * @noinspection PhpUnused
     */
    public function updateEventDefinition(
        int    $idEventCode,
        int    $idModule,
        int    $idSystem,
        string $description = null,
        string $alertText = null,
        int    $alertLevel = null
    ): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_UpdateEventDefinition %s', [$idEventCode, $idModule, $idSystem, $description, $alertText, $alertLevel])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //   _   _       _ _     _
    //  | | | | ___ | (_) __| | __ _ _   _
    //  | |_| |/ _ \| | |/ _` |/ _` | | | |
    //  |  _  | (_) | | | (_| | (_| | |_| |
    //  |_| |_|\___/|_|_|\__,_|\__,_|\__, |
    //                               |___/

    /**
     * Get list holiday.
     * VIEW: Svátky
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListHoliday(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_Holiday')->as('holiday');
    }


    /**
     * Save holiday.
     * STORED PROCEDURE: Uložení / editace svátku
     *
     * @param int|null    $idHoliday ID svátku; 0 = vytvoření nového svátku / HODNOTA = editace stávajícího svátku
     * @param int|null    $day den
     * @param int|null    $month měsíc
     * @param string|null $name název
     * @return bool
     * @noinspection PhpUnused
     */
    public function saveHoliday(
        int    $idHoliday = null,
        int    $day = null,
        int    $month = null,
        string $name = null
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_SaveHoliday %s', [$idHoliday, $day, $month, $name])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Delete holiday.
     * STORED PROCEDURE: Mazání svátku
     *
     * @param int $idHoliday ID svátku
     * @return bool
     * @noinspection PhpUnused
     */
    public function deleteHoliday(int $idHoliday): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_DeleteHoliday %s', [$idHoliday])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //   __  __           _       _      
    //  |  \/  | ___   __| |_   _| | ___ 
    //  | |\/| |/ _ \ / _` | | | | |/ _ \
    //  | |  | | (_) | (_| | |_| | |  __/
    //  |_|  |_|\___/ \__,_|\__,_|_|\___|
    //                                   

    /**
     * Get list module.
     * VIEW: Modul
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListModule(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_Module')->as('module');
    }


    /**
     * Update module.
     * STORED PROCEDURE: Editace modulu
     *
     * @param int         $idModule ID modulu
     * @param int         $idSystem reference [api_System].[IDSystem]: ID systému, ke kterému modul patří
     * @param string|null $name název modulu
     * @return bool
     * @noinspection PhpUnused
     */
    public function updateModule(
        int    $idModule,
        int    $idSystem,
        string $name = null
    ): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_UpdateModule %s', [$idModule, $idSystem, $name])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //   ____                                  _                         ____                       
    //  |  _ \ ___ _ __ ___  ___  _ __        / \   ___ ___ ___ ___ ___ / ___|_ __ ___  _   _ _ __  
    //  | |_) / _ \ '__/ __|/ _ \| '_ \      / _ \ / __/ __/ _ \ __/ __| |  _| '__/ _ \| | | | '_ \ 
    //  |  __/  __/ |  \__ \ (_) | | | |    / ___ \ (__ (__  __\__ \__ \ |_| | | | (_) | |_| | |_) |
    //  |_|   \___|_|  |___/\___/|_| |_|_____/   \_\___\___\___|___/___/\____|_|  \___/ \__,_| .__/ 
    //                                |_____|                                                |_|

    /**
     * Get list person access group.
     * VIEW: Přiřazení osob do přístupových skupin
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListPersonAccessGroup(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_Person_AccessGroup')->as('personAccessGroup');
    }


    /**
     * Save person access group.
     * STORED PROCEDURE: Uložení / editace přiřazení osob do přístupových skupin
     *
     * @param int $idPerson reference [api_Person].[IDPerson]: ID osoby
     * @param int $idAccessGroup reference [api_AccessGroup].[IDAccessGroup]: ID přístupové skupiny
     * @return bool
     * @noinspection PhpUnused
     */
    public function savePersonAccessGroup(
        int $idPerson,
        int $idAccessGroup
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_SavePerson_AccessGroup %s', [$idPerson, $idAccessGroup])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Delete person access group.
     * STORED PROCEDURE: Mazání přiřazení osob do přístupových skupin
     *
     * @param int $idPerson reference [api_Person].[IDPerson]: ID osoby
     * @param int $idAccessGroup reference [api_AccessGroup].[IDAccessGroup]: ID přístupové skupiny
     * @return bool
     * @noinspection PhpUnused
     */
    public function deletePersonAccessGroup(
        int $idPerson,
        int $idAccessGroup
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_DeletePerson_AccessGroup %s', [$idPerson, $idAccessGroup])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //   ____       _              _       _
    //  / ___|  ___| |__   ___  __| |_   _| | ___
    //  \___ \ / __| '_ \ / _ \/ _` | | | | |/ _ \
    //   ___) | (__| | | |  __/ (_| | |_| | |  __/
    //  |____/ \___|_| |_|\___|\__,_|\__,_|_|\___|
    //

    /**
     * Get list schedule.
     * VIEW: Časové plány
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListSchedule(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_Schedule')->as('schedule');
    }


    /**
     * Save schedule.
     * STORED PROCEDURE: Uložení / editace časového plánu
     *
     * @param int|null    $idSchedule ID časového plánu; 0 = vytvoření nového plánu / HODNOTA = editace stávajícího plánu
     * @param int|null    $number pořadové číslo časového plánu (1-64)
     * @param string|null $name název časového plánu
     * @param int|null    $mondayInterval1StartHour název časového plánu
     * @param int|null    $mondayInterval1StartMinute začátek intervalu 1 - minuty
     * @param int|null    $mondayInterval1StopHour konec intervalu 1 - hodiny
     * @param int|null    $mondayInterval1StopMinute konec intervalu 1 - minuty
     * @param int|null    $mondayInterval2StartHour začátek intervalu 2 - hodiny
     * @param int|null    $mondayInterval2StartMinute začátek intervalu 2 - minuty
     * @param int|null    $mondayInterval2StopHour konec intervalu 2 - hodiny
     * @param int|null    $mondayInterval2StopMinute konec intervalu 2 - minuty
     * @param int|null    $tuesdayInterval1StartHour
     * @param int|null    $tuesdayInterval1StartMinute
     * @param int|null    $tuesdayInterval1StopHour
     * @param int|null    $tuesdayInterval1StopMinute
     * @param int|null    $tuesdayInterval2StartHour
     * @param int|null    $tuesdayInterval2StartMinute
     * @param int|null    $tuesdayInterval2StopHour
     * @param int|null    $tuesdayInterval2StopMinute
     * @param int|null    $wednesdayInterval1StartHour
     * @param int|null    $wednesdayInterval1StartMinute
     * @param int|null    $wednesdayInterval1StopHour
     * @param int|null    $wednesdayInterval1StopMinute
     * @param int|null    $wednesdayInterval2StartHour
     * @param int|null    $wednesdayInterval2StartMinute
     * @param int|null    $wednesdayInterval2StopHour
     * @param int|null    $wednesdayInterval2StopMinute
     * @param int|null    $thursdayInterval1StartHour
     * @param int|null    $thursdayInterval1StartMinute
     * @param int|null    $thursdayInterval1StopHour
     * @param int|null    $thursdayInterval1StopMinute
     * @param int|null    $thursdayInterval2StartHour
     * @param int|null    $thursdayInterval2StartMinute
     * @param int|null    $thursdayInterval2StopHour
     * @param int|null    $thursdayInterval2StopMinute
     * @param int|null    $fridayInterval1StartHour
     * @param int|null    $fridayInterval1StartMinute
     * @param int|null    $fridayInterval1StopHour
     * @param int|null    $fridayInterval1StopMinute
     * @param int|null    $fridayInterval2StartHour
     * @param int|null    $fridayInterval2StartMinute
     * @param int|null    $fridayInterval2StopHour
     * @param int|null    $fridayInterval2StopMinute
     * @param int|null    $saturdayInterval1StartHour
     * @param int|null    $saturdayInterval1StartMinute
     * @param int|null    $saturdayInterval1StopHour
     * @param int|null    $saturdayInterval1StopMinute
     * @param int|null    $saturdayInterval2StartHour
     * @param int|null    $saturdayInterval2StartMinute
     * @param int|null    $saturdayInterval2StopHour
     * @param int|null    $saturdayInterval2StopMinute
     * @param int|null    $sundayInterval1StartHour
     * @param int|null    $sundayInterval1StartMinute
     * @param int|null    $sundayInterval1StopHour
     * @param int|null    $sundayInterval1StopMinute
     * @param int|null    $sundayInterval2StartHour
     * @param int|null    $sundayInterval2StartMinute
     * @param int|null    $sundayInterval2StopHour
     * @param int|null    $sundayInterval2StopMinute
     * @param int|null    $holidayInterval1StartHour
     * @param int|null    $holidayInterval1StartMinute
     * @param int|null    $holidayInterval1StopHour
     * @param int|null    $holidayInterval1StopMinute
     * @param int|null    $holidayInterval2StartHour
     * @param int|null    $holidayInterval2StartMinute
     * @param int|null    $holidayInterval2StopHour
     * @param int|null    $holidayInterval2StopMinute
     * @return bool
     * @noinspection PhpUnused
     */
    public function saveSchedule(
        int    $idSchedule = null,
        int    $number = null,
        string $name = null,
        int    $mondayInterval1StartHour = null,
        int    $mondayInterval1StartMinute = null,
        int    $mondayInterval1StopHour = null,
        int    $mondayInterval1StopMinute = null,
        int    $mondayInterval2StartHour = null,
        int    $mondayInterval2StartMinute = null,
        int    $mondayInterval2StopHour = null,
        int    $mondayInterval2StopMinute = null,
        int    $tuesdayInterval1StartHour = null,
        int    $tuesdayInterval1StartMinute = null,
        int    $tuesdayInterval1StopHour = null,
        int    $tuesdayInterval1StopMinute = null,
        int    $tuesdayInterval2StartHour = null,
        int    $tuesdayInterval2StartMinute = null,
        int    $tuesdayInterval2StopHour = null,
        int    $tuesdayInterval2StopMinute = null,
        int    $wednesdayInterval1StartHour = null,
        int    $wednesdayInterval1StartMinute = null,
        int    $wednesdayInterval1StopHour = null,
        int    $wednesdayInterval1StopMinute = null,
        int    $wednesdayInterval2StartHour = null,
        int    $wednesdayInterval2StartMinute = null,
        int    $wednesdayInterval2StopHour = null,
        int    $wednesdayInterval2StopMinute = null,
        int    $thursdayInterval1StartHour = null,
        int    $thursdayInterval1StartMinute = null,
        int    $thursdayInterval1StopHour = null,
        int    $thursdayInterval1StopMinute = null,
        int    $thursdayInterval2StartHour = null,
        int    $thursdayInterval2StartMinute = null,
        int    $thursdayInterval2StopHour = null,
        int    $thursdayInterval2StopMinute = null,
        int    $fridayInterval1StartHour = null,
        int    $fridayInterval1StartMinute = null,
        int    $fridayInterval1StopHour = null,
        int    $fridayInterval1StopMinute = null,
        int    $fridayInterval2StartHour = null,
        int    $fridayInterval2StartMinute = null,
        int    $fridayInterval2StopHour = null,
        int    $fridayInterval2StopMinute = null,
        int    $saturdayInterval1StartHour = null,
        int    $saturdayInterval1StartMinute = null,
        int    $saturdayInterval1StopHour = null,
        int    $saturdayInterval1StopMinute = null,
        int    $saturdayInterval2StartHour = null,
        int    $saturdayInterval2StartMinute = null,
        int    $saturdayInterval2StopHour = null,
        int    $saturdayInterval2StopMinute = null,
        int    $sundayInterval1StartHour = null,
        int    $sundayInterval1StartMinute = null,
        int    $sundayInterval1StopHour = null,
        int    $sundayInterval1StopMinute = null,
        int    $sundayInterval2StartHour = null,
        int    $sundayInterval2StartMinute = null,
        int    $sundayInterval2StopHour = null,
        int    $sundayInterval2StopMinute = null,
        int    $holidayInterval1StartHour = null,
        int    $holidayInterval1StartMinute = null,
        int    $holidayInterval1StopHour = null,
        int    $holidayInterval1StopMinute = null,
        int    $holidayInterval2StartHour = null,
        int    $holidayInterval2StartMinute = null,
        int    $holidayInterval2StopHour = null,
        int    $holidayInterval2StopMinute = null
    ): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_SaveSchedule %s', [
                $idSchedule, $number, $name,
                $mondayInterval1StartHour, $mondayInterval1StartMinute, $mondayInterval1StopHour, $mondayInterval1StopMinute,
                $mondayInterval2StartHour, $mondayInterval2StartMinute, $mondayInterval2StopHour, $mondayInterval2StopMinute,
                $tuesdayInterval1StartHour, $tuesdayInterval1StartMinute, $tuesdayInterval1StopHour, $tuesdayInterval1StopMinute,
                $tuesdayInterval2StartHour, $tuesdayInterval2StartMinute, $tuesdayInterval2StopHour, $tuesdayInterval2StopMinute,
                $wednesdayInterval1StartHour, $wednesdayInterval1StartMinute, $wednesdayInterval1StopHour, $wednesdayInterval1StopMinute,
                $wednesdayInterval2StartHour, $wednesdayInterval2StartMinute, $wednesdayInterval2StopHour, $wednesdayInterval2StopMinute,
                $thursdayInterval1StartHour, $thursdayInterval1StartMinute, $thursdayInterval1StopHour, $thursdayInterval1StopMinute,
                $thursdayInterval2StartHour, $thursdayInterval2StartMinute, $thursdayInterval2StopHour, $thursdayInterval2StopMinute,
                $fridayInterval1StartHour, $fridayInterval1StartMinute, $fridayInterval1StopHour, $fridayInterval1StopMinute,
                $fridayInterval2StartHour, $fridayInterval2StartMinute, $fridayInterval2StopHour, $fridayInterval2StopMinute,
                $saturdayInterval1StartHour, $saturdayInterval1StartMinute, $saturdayInterval1StopHour, $saturdayInterval1StopMinute,
                $saturdayInterval2StartHour, $saturdayInterval2StartMinute, $saturdayInterval2StopHour, $saturdayInterval2StopMinute,
                $sundayInterval1StartHour, $sundayInterval1StartMinute, $sundayInterval1StopHour, $sundayInterval1StopMinute,
                $sundayInterval2StartHour, $sundayInterval2StartMinute, $sundayInterval2StopHour, $sundayInterval2StopMinute,
                $holidayInterval1StartHour, $holidayInterval1StartMinute, $holidayInterval1StopHour, $holidayInterval1StopMinute,
                $holidayInterval2StartHour, $holidayInterval2StartMinute, $holidayInterval2StopHour, $holidayInterval2StopMinute,
            ])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Delete schedule.
     * STORED PROCEDURE: Mazání časového plánu
     *
     * @param int $idSchedule ID časového plánu
     * @return bool
     * @noinspection PhpUnused
     */
    public function deleteSchedule(int $idSchedule): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NoDataResult $result */
        $result = $this->connection->command()
            ->exec('api_DeleteSchedule %s', [$idSchedule])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //   ____            _                 
    //  / ___| _   _ ___| |_ ___ _ __ ___  
    //  \___ \| | | / __| __/ _ \ '_ ` _ \ 
    //   ___) | |_| \__ \ |_  __/ | | | | |
    //  |____/ \__, |___/\__\___|_| |_| |_|
    //         |___/                       

    /**
     * Get list system.
     * VIEW: Systém
     *
     * @param string $select
     * @return IDataSource
     * @noinspection PhpUnused
     */
    public function getListSystem(string $select = '*'): IDataSource
    {
        return $this->connection->select($select)
            ->from('api_System')->as('system');
    }


    /**
     * Update system.
     * STORED PROCEDURE: Editace systému
     *
     * @param int    $idSystem ID systému k editaci
     * @param string $name název systému
     * @return bool
     * @noinspection PhpUnused
     */
    public function updateSystem(
        int    $idSystem,
        string $name
    ): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_UpdateSystem %s', [$idSystem, $name])
            ->execute();
        return $result->getRowCount() > 0;
    }

    //       _                     _                                _                    
    //   ___| |_ ___  _ __ ___  __| |  _ __  _ __ ___   ___ ___  __| |_   _ _ __ ___ ___ 
    //  / __| __/ _ \| '__/ _ \/ _` | | '_ \| '__/ _ \ / __/ _ \/ _` | | | | '__/ _ \ __|
    //  \__ \ |_ (_) | | |  __/ (_| | | |_) | | | (_) | (__  __/ (_| | |_| | | |  __\__ \
    //  |___/\__\___/|_|  \___|\__,_| | .__/|_|  \___/ \___\___|\__,_|\__,_|_|  \___|___/
    //                                |_|                                                

    /**
     * Execute user event.
     * STORED PROCEDURE: Spuštění uživatelské události
     *
     * @param int $idSystem reference [api_System].[IDSystem]: ID systému
     * @param int $idUserEvent ID uživatelské události
     * @return bool
     * @noinspection PhpUnused
     */
    public function executeUserEvent(
        int $idSystem,
        int $idUserEvent
    ): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_ExecuteUserEvent %s', [$idSystem, $idUserEvent])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Update access groups schedules and holidays.
     * STORED PROCEDURE: Nahrání přístupových skupin, časových plánů a svátků
     *
     * @return bool
     * @noinspection PhpUnused
     */
    public function updateAccessGroupsSchedulesAndHolidays(): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_UploadAccessGroupsSchedulesAndHolidays')
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Update access for person.
     * STORED PROCEDURE: Aktualizace oprávnění uživatele
     *
     * @param int $idPerson reference [api_Person].[IDPerson]: ID osoby
     * @return bool
     * @noinspection PhpUnused
     */
    public function updateAccessForPerson(int $idPerson): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_UpdateAccessForPerson %s', [$idPerson])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Release person.
     * STORED PROCEDURE: Uvolnění uživatele
     *
     * @param int $idPerson reference [api_Person].[IDPerson]: ID osoby
     * @return bool
     * @noinspection PhpUnused
     */
    public function releasePerson(int $idPerson): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_ReleasePerson %s', [$idPerson])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Release card.
     * STORED PROCEDURE: Uvolnění karty
     *
     * @param int $idCard reference [api_Card].[IDCard]: ID karty
     * @return bool
     * @noinspection PhpUnused
     */
    public function releaseCard(int $idCard): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_ReleaseCard %s', [$idCard])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Remote open door.
     *
     * @return bool
     * @noinspection PhpUnused
     */
    public function remoteOpenDoor(): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_RemoteOpenDoor')
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Set register.
     * STORED PROCEDURE: Nastavení hodnoty registru
     *
     * @param int $idSystem reference [api_System].[IDSystem]:ID systému
     * @param int $idRegister ID registru
     * @param int $value hodnota [1-250]
     * @return bool
     * @noinspection PhpUnused
     */
    public function setRegister(
        int $idSystem,
        int $idRegister,
        int $value
    ): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_SetRegister %s', [$idSystem, $idRegister, $value])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Set timer.
     * STORED PROCEDURE: Nastavení hodnoty časovače
     *
     * @param int $idSystem reference [api_System].[IDSystem]: ID systému
     * @param int $idTimer ID časovače
     * @param int $value hodnota
     * @return bool
     * @noinspection PhpUnused
     */
    public function setTimer(
        int $idSystem,
        int $idTimer,
        int $value
    ): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_SetTimer %s', [$idSystem, $idTimer, $value])
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Upload data.
     * STORED PROCEDURE: nahrání dat
     *
     * @return bool
     * @noinspection PhpUnused
     */
    public function uploadData(): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_UploadData')
            ->execute();
        return $result->getRowCount() > 0;
    }


    /**
     * Upload scheduleX.
     * STORED PROCEDURE: Nahrání konkrétního časového plánu
     *
     * @param int $scheduleIdNumber reference [api_Schedule].[IDNumber]: Pořadové číslo časového plánu (1-64)
     * @return bool
     * @noinspection PhpUnused
     */
    public function uploadScheduleX(int $scheduleIdNumber): bool
    {
        /** @var NoDataResult $result */
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->connection->command()
            ->exec('api_UploadScheduleX %s', [$scheduleIdNumber])
            ->execute();
        return $result->getRowCount() > 0;
    }
}
