<?php
/**
 * @package    ApiBundle\Service\Request
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Service\Request;

use BiBundle\Entity\Model\DataSourceConnection;
use BiBundle\Entity\Resource;
use BiBundle\Service\UploadResource;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ResourceRequest
{
    /**
     * @var RequestStack
     */
    private $request;
    /**
     * @var UploadResource
     */
    private $uploadResource;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(RequestStack $request, UploadResource $uploadResource, ValidatorInterface $validator)
    {
        $this->request = $request;
        $this->uploadResource = $uploadResource;
        $this->validator = $validator;
    }

    /**
     * @param array $params
     * @return \BiBundle\Entity\Resource
     */
    public function getResource(array $params)
    {
        $request = $this->getRequest();
        $connType = $params['connection_type'];
        $resource = new \BiBundle\Entity\Resource();

        // files or database connections
        if (in_array(strtolower($connType), [Resource::TYPE_CSV, Resource::TYPE_EXCEL, Resource::TYPE_TEXT])) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $resourceFile */
            $resourceFile = $request->getCurrentRequest()->files->get('resource_file');
            if (!$resourceFile) {
                throw new HttpException(400, 'Файл не загружен');
            }
            $mime = $resourceFile->getClientMimeType();

            $uploadResourceService = $this->getUploadResource();
            $uploadResourceService->setUploadPath('uploads/resource/'.date("Ymd"));

            $uploadedResourcePathArray = $uploadResourceService->upload($resourceFile);

            $resource->addFile(strtolower($connType), $uploadedResourcePathArray['path'], $mime);
        } else {// database connections
            $connection = DataSourceConnection::fromUserInput($params);
            $this->validate($connection);
            $connectionData = $connection->toArray();
            $resource->setSettings(['type' => $connectionData['type'], 'connection' => $connectionData]);
        }

        return $resource;
    }

    /**
     * @return RequestStack
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return UploadResource
     */
    public function getUploadResource()
    {
        return $this->uploadResource;
    }

    /**
     * @param mixed $value The value to validate
     * @param Constraint|Constraint[] $constraints The constraint(s) to validate
     *                                             against
     * @param array|null $groups The validation groups to
     *                                             validate. If none is given,
     *                                             "Default" is assumed
     * @return bool
     * @throws ValidatorException
     */
    protected function validate($value, $constraints = null, $groups = null)
    {
        $validator = $this->getValidator();
        $result = $validator->validate($value, $constraints, $groups);
        if ($result->count() > 0) {
            // throw first exception in validation constraints
            /** @var \Symfony\Component\Validator\ConstraintViolation $validationConstraint */
            foreach ($result as $validationConstraint) {
                $messageTemplate = "Field <%s> has value <%s>. %s";
                $message = sprintf($messageTemplate,
                    DataSourceConnection::getReversedInputMapping($validationConstraint->getPropertyPath()),
                    $validationConstraint->getInvalidValue(),
                    $validationConstraint->getMessage());

                throw new ValidatorException($message);
            }
        }
        return true;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }
}