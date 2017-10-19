<?php

namespace App\API\V1\Controllers;

use App\API\V1\Repositories\AlbumRepository;
use TempestTools\Crud\Contracts\Events\SimpleEventContract;
use TempestTools\Crud\Orm\Transformers\ToArrayTransformer;

/** @noinspection LongInheritanceChainInspection */
class AlbumController extends APIControllerAbstract
{

    public function __construct(AlbumRepository $repo, ToArrayTransformer $arrayTransformer)
    {
        $this->setRepo($repo);
        $this->setTransformer($arrayTransformer);
        parent::__construct();
    }
    /** @noinspection SenselessMethodDuplicationInspection */

    /**
     * @return array
     */
    public function getTTConfig(): array
    {
        return [
            'default'=>[
                'GET'=>[],
                'POST'=>[],
                'PUT'=>[],
                'DELETE'=>[]
            ],
            'user/users'=>[
                'GET'=>[
                    'resourceIdConversion'=>[
                        'user'=>'userResourceId'
                    ]
                ],
            ],
            'guest/artists'=>[
                'GET'=>[
                    'resourceIdConversion'=>[
                        'user'=>'artistResourceId'
                    ]
                ],
            ]
        ];
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onInit (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPreIndex (SimpleEventContract $event):void
    {
        //
    }


    /**
     * @param SimpleEventContract $event
     */
    public function onPostIndex (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPreStore (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPostStore (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPreShow (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPostShow (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPreUpdate (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPostUpdate (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPreDestroy (SimpleEventContract $event):void
    {
        //
    }

    /**
     * @param SimpleEventContract $event
     */
    public function onPostDestroy (SimpleEventContract $event):void
    {
        //
    }

}
