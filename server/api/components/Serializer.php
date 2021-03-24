<?php

namespace api\components;
use Yii;
use yii\rest\Serializer as Ser;

class Serializer extends Ser
{
    public $restData;
    protected function serializeDataProvider($dataProvider)
    {
        if ($this->preserveKeys) {
            $models = $dataProvider->getModels();
        } else {
            $models = array_values($dataProvider->getModels());
        }
        $models = $this->serializeModels($models);

        if (($pagination = $dataProvider->getPagination()) !== false) {
            $this->addPaginationHeaders($pagination);}


        if ($this->request->getIsHead()) {
            return null;
        } elseif ($this->collectionEnvelope === null) {
            return $models;
        }

        $result = [
            $this->collectionEnvelope => $models,
        ];
        $model = $this->restData !== null?$this->restData:false;
        $result['restData'] = !!$model?$model::getExtra():false;


        if ($pagination !== false) {
            return array_merge($result, $this->serializePagination($pagination));
        }
        return $result;

    }

}