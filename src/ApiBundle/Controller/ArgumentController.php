<?php

namespace ApiBundle\Controller;

use BiBundle\Entity\Purchase;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\Route;
use BiBundle\Service\Upload\FilePathStrategy;

class ArgumentController extends RestController
{
    /**
     *
     *
     * @ApiDoc(
     *  section="8. Аргументы",
     *  resource=true,
     *  description="Создание связи Активация+Аргумент+Источник+Таблица+Столбец",
     *  statusCodes={
     *          201="Создано",
     *          400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     * @Route("/activation/{activation}/resource/{resource}/argument/{argument}", requirements={"activation": "\d+", "resource": "\d+", "argument": "\d+"})
     *
     * @RequestParam(name="table_name", allowBlank=false, requirements=".+", description="Наименование таблицы")
     * @RequestParam(name="column_name", allowBlank=false, requirements=".+", description="Уникальное наименование столбца")
     *
     * @param \BiBundle\Entity\Activation $activation
     * @param \BiBundle\Entity\Resource $resource
     * @param \BiBundle\Entity\Argument $argument
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     *
     * @return Response
     */

    public function postActivationResourceArgumentAction(\BiBundle\Entity\Activation $activation, \BiBundle\Entity\Resource $resource, \BiBundle\Entity\Argument $argument, ParamFetcher $paramFetcher)
    {
        $params = $this->getParams($paramFetcher, 'ArgumentBond');

        $argumentBond = new \BiBundle\Entity\ArgumentBond();
        $argumentBond->setActivation($activation);
        $argumentBond->setResource($resource);
        $argumentBond->setArgument($argument);
        $argumentBond->setTableName($params['table_name']);
        $argumentBond->setColumnName($params['column_name']);

        $argumentBondRepository = $this->get('bi.argument_bond.service');
        $argumentBondRepository->save($argumentBond);

        $data = [
            'id' => $argumentBond->getId()
        ];
        $view = $this->view($data);
        return $this->handleView($view);
    }

    /**
     *
     *
     * @ApiDoc(
     *  section="8. Аргументы",
     *  resource=true,
     *  description="Получение связи аргумент->данные",
     *  statusCodes={
     *          200="Успех",
     *          400="Ошибки валидации"
     *     },
     *  headers={
     *      {
     *          "name"="X-AUTHORIZE-TOKEN",
     *          "description"="access key header",
     *          "required"=true
     *      }
     *    }
     * )
     *
     * @Route("/activation/{activation}/argument_bonds", requirements={"activation": "\d+"})
     *
     * @param \BiBundle\Entity\Activation $activation
     *
     * @return Response
     */
    public function getActivationArgumentBondsAction(\BiBundle\Entity\Activation $activation)
    {
        $argumentBondService = $this->get('bi.argument_bond.service');
        $argumentBondList = $argumentBondService->getArgumentBondList($activation);
        $data = [];
        if($argumentBondList) {
            foreach ($argumentBondList as $argument) {
                $data[] = [
                    'argument' => $argument->getArgument()->getId(),
                    'resource' => $argument->getResource()->getId(),
                    'table_name' => $argument->getTableName(),
                    'column_name' => $argument->getColumnName()
                ];
            }
        }

        $view = $this->view($data);
        return $this->handleView($view);
    }
}