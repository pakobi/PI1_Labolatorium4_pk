<?php

namespace Application\Model;

use Laminas\Db\Adapter as DbAdapter;
use Laminas\Db\Sql\Sql;

class Autor implements DbAdapter\AdapterAwareInterface
{
    use DbAdapter\AdapterAwareTrait;

    public function pobierzSlownik(): array
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $select = $sql->select('autorzy');
        $select->order('nazwisko');

        $selectString = $sql->buildSqlString($select);
        $wyniki = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        $temp = [];
        foreach ($wyniki as $rek) {
            $temp[$rek->id] = $rek->imie . ' ' . $rek->nazwisko;
        }

        return $temp;
    }

    public function dodaj($dane)
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $insert = $sql->insert('autorzy');
        $insert->values([
            'imie' => $dane->imie,
            'nazwisko' => $dane->nazwisko,
        ]);

        $selectString = $sql->buildSqlString($insert);
        $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        try {
            return $wynik->getGeneratedValue();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function pobierzImieNazwisko(int $idAutora): string
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $select = $sql->select('autorzy');
        $select->where(['id' => $idAutora]);

        $selectString = $sql->buildSqlString($select);
        $wyniki = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        $autor = $wyniki->current();
        return $autor->imie . ' ' . $autor->nazwisko;
    }

    public function pobierz(int $id)
    {
        $dbAdapter = $this->adapter;
        $sql = new Sql($dbAdapter);
        $select = $sql->select('autorzy');
        $select->where(['id' => $id]);
        $select->order('nazwisko');

        $selectString = $sql->buildSqlString($select);
        $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($wynik->count()) {
            return $wynik->current();
        } else {
            return [];
        }
    }

    public function pobierzWszystko()
    {
        $dbAdapter = $this->adapter;
        $sql = new Sql($dbAdapter);
        $select = $sql->select('autorzy');
        $select->order('nazwisko');
        $selectString = $sql->buildSqlString($select);

        return $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }

    public function aktualizuj(int $id, $dane)
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $update = $sql->update('autorzy');
        $update->set([
            'imie' => $dane->imie,
            'nazwisko' => $dane->nazwisko,
        ]);
        $update->where(['id' => $id]);

        $selectString = $sql->buildSqlString($update);
        $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }

    public function usun(int $id): void
    {

        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $deleteKsiazki = $sql->delete('ksiazki');
        $deleteKsiazki->where(['id_autora' => $id]);

        $selectString = $sql->buildSqlString($deleteKsiazki);
        $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        $delete = $sql->delete('autorzy');
        $delete->where(['id' => $id]);

        $selectString = $sql->buildSqlString($delete);
        $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }
}