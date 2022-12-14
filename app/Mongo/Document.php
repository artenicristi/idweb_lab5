<?php

namespace App\Mongo;

use App\Services\MongoConnection;
use App\Services\MongoService;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;
use MongoDB\DeleteResult;
use MongoDB\GridFS\Bucket;
use MongoDB\InsertOneResult;
use MongoDB\Model\BSONDocument;
use MongoDB\UpdateResult;

abstract class Document implements HydrateableInterface
{
    /** @var string */
    public static $collectionName = null;

    /** @var string */
    public static $gridFsBucketName = null;

    /**
     * @var MongoService
     */
    protected static $mongoService;

    /**
     * @var string
     */
    protected static $connection = null;

    public static function findOneAndUpdate(array $query, array $update, array $options = [])
    {
        $response = static::getCollection()->findOneAndUpdate($query, $update, $options);

        return static::prepareResponse($response);
    }

    public static function deleteOneById($id): DeleteResult
    {
        return static::getCollection()->deleteOne(['_id' => Document::toObjectId($id)]);
    }

    public static function insertOne(array $data, array $options = []): InsertOneResult
    {
        return static::getCollection()->insertOne($data, $options);
    }

    public static function updateOne(array $query, array $update, array $options = []): UpdateResult
    {
        return static::getCollection()->updateOne($query, $update, $options);
    }

    public static function updateMany(array $query, array $update, array $options = []): UpdateResult
    {
        return static::getCollection()->updateMany($query, $update, $options);
    }

    public static function softDeleteById($id): UpdateResult
    {
        return static::getCollection()->updateOne(
            ['_id' => self::toObjectId($id)], ['$set' => ['deletedAt' => Carbon::now()->toDateTimeString()]]
        );
    }

    public static function restoreById($id): UpdateResult
    {
        return static::getCollection()->updateOne(
            ['_id' => self::toObjectId($id)], ['$unset' => ['deletedAt' => true]]
        );
    }

    public static function distinct(string $field, array $query = [], array $options = []): array
    {
        return static::getCollection()->distinct($field, $query, $options);
    }

    /**
     * @return MongoConnection
     */
    public static function getConnection()
    {
        if (isset(static::$connection)) {
            self::$mongoService->getConnection(static::$connection);
        }

        return self::$mongoService->getDefaultConnection();
    }

    public static function getCollection(): Collection
    {
        $db = static::getConnection()->getDb();

        if (!empty(static::$gridFsBucketName)) {
            $collection = $db
                ->selectGridFSBucket(array('bucketName' => static::$gridFsBucketName))
                ->getFilesCollection()
            ;
        } else {
            $collection = $db->selectCollection(static::$collectionName);
        }

        return $collection;
    }

    public static function getGridFsBucket(): Bucket
    {
        return static::getConnection()->getDb()->selectGridFSBucket(array('bucketName' => static::$gridFsBucketName));
    }

    public static function setMongoService(MongoService $mongoService)
    {
        self::$mongoService = $mongoService;
    }

    public static function find(array $query, array $options = []): SingleDocumentResponse
    {
        $response = static::getCollection()->find($query, $options);

        return static::prepareResponse($response);
    }

    public static function aggregate(array $pipeline, array $options = []): SingleDocumentResponse
    {
        $response = static::getCollection()->aggregate($pipeline, $options);

        return static::prepareResponse($response);
    }

    public static function aggregateOne(array $pipeline, array $options = []): SingleDocumentResponse
    {
        $pipeline[] = ['$limit' => 1];
        $response = static::getCollection()->aggregate($pipeline, $options);

        return static::prepareResponse($response);
    }

    /**
     * @param ObjectId|string $documentId
     * @return array|object|null
     */
    public static function findOneById($documentId, array $options = []): SingleDocumentResponse
    {
        return static::findOne(['_id' => static::toObjectId($documentId)], $options);
    }

    /**
     * @param string|ObjectId $documentId
     */
    public static function toObjectId($documentId): ObjectId
    {
        if (is_string($documentId)) {
            $documentId = new ObjectId($documentId);
        }

        return $documentId;
    }

    /**
     * @param string[]|ObjectId[] $documentIds
     * @return ObjectId[]
     */
    public static function toObjectIds(array $documentIds): array
    {
        foreach ($documentIds as $idx => $id) {
            $documentIds[$idx] = static::toObjectId($id);
        }

        return $documentIds;
    }

    /**
     * @param array $query
     * @param array $options
     * @return array|object|null
     */
    public static function findOne(array $query, array $options = []): SingleDocumentResponse
    {
        $response = static::getCollection()->findOne($query, $options);

        return static::prepareResponse($response, false);
    }

    public static function count(array $query, array $options = []): int
    {
        return static::getCollection()->countDocuments($query, $options);
    }

    public static function createDbRef($id, string $collectionName = null, string $databaseName = null)
    {
        $collectionName = $collectionName ?? static::$collectionName;

        if (empty($collectionName) && !empty(static::$gridFsBucketName)) {
            $collectionName = static::$gridFsBucketName . '.files';
        }

        if (empty($collectionName)) {
            throw new \Exception('Cannot create a DBRef without a collection name');
        }

        if (!empty($databaseName)) {
            $reference = [
                '$ref' => $collectionName,
                '$id' => static::toObjectId($id),
                '$db' => $databaseName,
            ];
        } else {
            $reference = [
                '$ref' => $collectionName,
                '$id' => static::toObjectId($id),
            ];
        }

        return $reference;
    }

    /**
     * @param BSONDocument|array $document
     * @return array
     */
    public static function documentToDbRef($document): array
    {
        return static::createDbRef(
            $document['_id'],
            static::getCollection()->getCollectionName(),
            static::getCollection()->getDatabaseName()
        );
    }

    public static function findAsDbRefs(array $query, array $options = []): array
    {
        $options['prjoection'] = ['_id' => 1];

        $result = static::find($query, $options)->getRaw();

        $dbRefs = [];
        foreach ($result as $item) {
            $dbRefs[] = static::documentToDbRef($item);
        }

        return $dbRefs;
    }

    protected static function prepareResponse($result, $isMultiple = true): SingleDocumentResponse
    {
        if ($isMultiple) {
            $response = new MultipleDocumentsResponse($result, static::class);
        } else {
            $response = new SingleDocumentResponse($result, static::class);
        }

        return $response;
    }

    public function __get($propertyName)
    {
        $methodName = 'get' . ucfirst($propertyName) . 'Attribute';

        if (method_exists($this, $methodName)) {
            return call_user_func([$this, $methodName]);
        }

        throw new \Exception("Neither method $methodName nor property $propertyName is defined in " .get_class($this));
    }
}
