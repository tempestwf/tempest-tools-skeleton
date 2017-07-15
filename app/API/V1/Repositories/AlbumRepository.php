<?php

namespace App\API\V1\Repositories;
use App\Repositories\Repository;
use App\API\V1\Entities\Album;
use TempestTools\Crud\Doctrine\Events\GenericEventArgs;

/** @noinspection LongInheritanceChainInspection */

/**
 * AlbumRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class AlbumRepository extends Repository
{
    protected /** @noinspection ClassOverridesFieldOfSuperClassInspection */
        $entity = Album::class;

    public function preStart(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $array = $this->getArrayHelper()->getArray();
        if (!isset($array['repoEvents'])) {
            $array['repoEvents'] = [];
        }
        $array['repoEvents']['preStart'] = $e->getArgs()->getArrayCopy();
    }

    public function preStop(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['preStop']=$e;
    }

    public function preCreateBatch(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['preCreateBatch']=$e;
    }

    public function preCreate(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['preCreate']=$e;
    }

    public function validateCreate(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['validateCreate']=$e;
    }

    public function verifyCreate(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['verifyCreate']=$e;
    }

    public function processResultsCreate(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['processResultsCreate']=$e;
    }

    public function postCreate(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['postCreate']=$e;
    }

    public function postCommitCreate(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['postCommitCreate']=$e;
    }

    public function postCreateBatch(GenericEventArgs $e) {
        /** @noinspection NullPointerExceptionInspection */
        $this->getArrayHelper()->getArray()['repoEvents']['postCreateBatch']=$e;
    }

    /**
     * @return array
     */
    public function getTTConfig(): array
    {
        return [
            'default'=>[],
            'testing'=>[]
        ];
    }
}
