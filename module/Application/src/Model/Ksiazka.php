<?php

namespace Application\Model;

use Laminas\Db\Adapter as DbAdapter;
use Laminas\Db\Sql\Sql;

class Ksiazka implements DbAdapter\AdapterAwareInterface
{
    use DbAdapter\AdapterAwareTrait;

    public function dodaj($dane)
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $insert = $sql->insert('ksiazki');
        $insert->values([
            'id_autora' => $dane->id_autora,
            'tytul' => $dane->tytul,
            'opis' => $dane->opis,
            'cena' => $dane->cena,
            'liczba_stron' => $dane->liczba_stron,
        ]);

        $selectString = $sql->buildSqlString($insert);
        $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        try {
            return $wynik->getGeneratedValue();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function pobierzWszystko()
    {
        $dbAdapter = $this->adapter;
        $sql = new Sql($dbAdapter);
        $select = $sql->select();
        $select->from(['k' => 'ksiazki']);
        $select->join(['a' => 'autorzy'], 'k.id_autora = a.id', ['imie', 'nazwisko']);
        $select->order('k.tytul');
        $selectString = $sql->buildSqlString($select);

        return $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }

    public function pobierz(int $id)
    {
        $dbAdapter = $this->adapter;
        $sql = new Sql($dbAdapter);
        $select = $sql->select('ksiazki');
        $select->where(['id' => $id]);
        $select->order('tytul');

        $selectString = $sql->buildSqlString($select);
        $wynik = $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);

        if ($wynik->count()) {
            return $wynik->current();
        } else {
            return [];
        }
    }

    public function aktualizuj(int $id, $dane)
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $update = $sql->update('ksiazki');
        $update->set([
            'id_autora' => $dane->id_autora,
            'tytul' => $dane->tytul,
            'opis' => $dane->opis,
            'cena' => $dane->cena,
            'liczba_stron' => $dane->liczba_stron,
        ]);
        $update->where(['id' => $id]);

        $selectString = $sql->buildSqlString($update);
        $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }

    public function usun(int $id): void
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $delete = $sql->delete('ksiazki');
        $delete->where(['id' => $id]);

        $selectString = $sql->buildSqlString($delete);
        $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }

    public function usunKsiazkiAutora(int $idAutora): void
    {
        $dbAdapter = $this->adapter;

        $sql = new Sql($dbAdapter);
        $delete = $sql->delete('ksiazki');
        $delete->where(['id_autora' => $idAutora]);

        $selectString = $sql->buildSqlString($delete);
        $dbAdapter->query($selectString, $dbAdapter::QUERY_MODE_EXECUTE);
    }
}