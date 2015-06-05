<?php
namespace Music\Lib\Storage;

require 'S3Instance.php';

class S3 implements IStorage
{
    private $awsAccessKey = '';
    private $awsSecretKey = '';
    private $awsBucketName = '';

    /**
     * @return string
     */
    public function getAwsAccessKey()
    {
        return $this->awsAccessKey;
    }

    /**
     * @param string $awsAccessKey
     */
    public function setAwsAccessKey($awsAccessKey)
    {
        $this->awsAccessKey = $awsAccessKey;
    }

    /**
     * @return string
     */
    public function getAwsSecretKey()
    {
        return $this->awsSecretKey;
    }

    /**
     * @param string $awsSecretKey
     */
    public function setAwsSecretKey($awsSecretKey)
    {
        $this->awsSecretKey = $awsSecretKey;
    }

    /**
     * @return string
     */
    public function getAwsBucketName()
    {
        return $this->awsBucketName;
    }

    /**
     * @param string $awsBucketName
     */
    public function setAwsBucketName($awsBucketName)
    {
        $this->awsBucketName = $awsBucketName;
    }

    function getFile($hash, $headers = array())
    {
        $s3  = $this->getS3Instance();
        $res = $s3->getObject($this->getAwsBucketName(), $hash, false, $headers);

        if ($res === false) {
            return false;
        }

        return $res->body;
    }

    function getInfo($hash)
    {
        $s3  = $this->getS3Instance();
        $res = $s3->getObjectInfo($this->getAwsBucketName(), $hash, true);

        return $res;
    }

    function saveFile($filePath, $hash)
    {
        if ($hash === false) {
            return false;
        }

        $s3 = $this->getS3Instance();

        return $s3->putObject(\S3Instance::inputFile($filePath), $this->getAwsBucketName(), $hash);
    }

    function saveInfo($hash, $info = array())
    {
        $s3 = $this->getS3Instance();

        return $s3->copyObject(
            $this->getAwsBucketName(),
            $hash,
            $this->getAwsBucketName(),
            $hash,
            \S3Instance::ACL_PRIVATE,
            $info
        );
    }

    function deleteFile($hash)
    {
        $s3 = $this->getS3Instance();

        return $s3->deleteObject($this->getAwsBucketName(), $hash);
    }

    public function getS3Instance()
    {
        $s3 = new \S3Instance($this->getAwsAccessKey(), $this->getAwsSecretKey());

        return $s3;
    }
}
