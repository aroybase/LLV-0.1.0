<?php
/**
 * PHP Class Activity.php
 * PHP Version 5
 *
 * @category    : default
 * @package     : default
 * @license     : Not for free use
 * @date        : 04/08/12
 * @author      : aroy <contact@aroy.fr>
 */

class Llv_Services_Activity
{
    /** @var $_entity Llv_Entity_Activity */
    protected static $_entity;
    /** @var Llv_Entity_Uploader */
    protected static $_uploaderEntity;

    /**
     * @return \Llv_Entity_Activity
     */
    public static function getEntity()
    {
        return self::$_entity;
    }

    /**
     * @return \Llv_Entity_Uploader
     */
    public static function getUploaderEntity()
    {
        return self::$_uploaderEntity;
    }

    /**
     *
     */
    public function __construct($entity = null, $uploaderEntity = null)
    {
        if (is_null($entity)) {
            self::$_entity = new Llv_Entity_Activity();
        } elseif ($entity instanceof Llv_Entity_Activity_Interface) {
            self::$_entity = $entity;
        } else {
            throw new InvalidArgumentException(_('ERROR_EXCEPTION_INVALIDARGUMENT'));
        }

        if (is_null($uploaderEntity)) {
            self::$_uploaderEntity = new Llv_Entity_Uploader();
        } elseif ($uploaderEntity instanceof Llv_Entity_Uploader_Interface) {
            self::$_uploaderEntity = $uploaderEntity;
        } else {
            throw new InvalidArgumentException(_('ERROR_EXCEPTION_INVALIDARGUMENT'));
        }
    }

    /** ••••••••••••••••••••••••••••••••••••••••••••••••••••••• */

    /**
     * @param Llv_Services_Message_Header           $header
     * @param Llv_Services_Activity_Filter_Activity $filter
     *
     * @return Llv_Services_Activity_Message_Activity
     */
    public function getOne(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Filter_Activity $filter
    )
    {
        $message = new Llv_Services_Activity_Message_Activity();
        try {
            $entityFilter = new Llv_Entity_Activity_Filter_Activity();
            $entityFilter->id = $filter->id;
            $entityFilter->idLangue = $filter->idLangue;
            $entityFilter->online = $filter->online;
            $entityFilter->spotlight = $filter->spotlight;
            $activite = $this->getEntity()->getOne($entityFilter);
            if (!is_null($activite)) {
                /** On ajoute la catégorie */
                $categoryFilter = new Llv_Services_Activity_Filter_Category();
                $categoryFilter->id = $activite->category->id;
                $categorie = $this->categoryGetOne($header, $categoryFilter);
                $activite->category = $categorie->categorie;

                $illuFilter = new Llv_Services_Activity_Filter_File();
                $illuFilter->idActivity = $activite->id;
                $illustrations = $this->getActivityFiles($header, $illuFilter);
                $activite->illustrations = $illustrations->files;

                $message->activite = $activite;
                $message->success = true;
            }
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header           $header
     * @param Llv_Services_Activity_Filter_Activity $filter
     *
     * @return Llv_Services_Activity_Message_Activity
     */
    public function getAll(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Filter_Activity $filter
    )
    {
        $message = new Llv_Services_Activity_Message_Activity();
        try {
            $entityFilter = new Llv_Entity_Activity_Filter_Activity();
            if (isset($filter->online)) {
                $entityFilter->online = $filter->online;
            }
            $entityFilter->idLangue = $filter->idLangue;
            $activites = $this->getEntity()->getAll($entityFilter);
            /** On ajoute les catégories */
            $result = array();
            /** @var $activite Llv_Dto_Activity */
            foreach ($activites as $activite) {
                if (!is_null($activite)) {
                    $categoryFilter = new Llv_Services_Activity_Filter_Category();
                    $categoryFilter->id = $activite->category->id;
                    $categorie = $this->categoryGetOne($header, $categoryFilter);
                    $activite->category = $categorie->categorie;

                    $illuFilter = new Llv_Services_Activity_Filter_File();
                    $illuFilter->idActivity = $activite->id;
                    $illustrations = $this->getActivityFiles($header, $illuFilter);
                    $activite->illustrations = $illustrations->files;
                    $result[] = $activite;
                }
            }
            $message->activites = $result;
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header        $header
     * @param Llv_Services_Activity_Request_Edit $request
     *
     * @return Llv_Services_Activity_Message_Activity
     */
    public function editRow(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Request_Edit $request
    )
    {
        $message = new Llv_Services_Activity_Message_Activity();
        try {
            $entityRequest = new Llv_Entity_Activity_Request_Edit();
            $entityRequest->id = isset($request->id)
                && !is_null($request->id)
                && !empty($request->id)
                ? $request->id
                : null;
            $entityRequest->idCategorie = $request->idCategorie;
            $entityRequest->position = $request->position;
            if (isset($request->online)) {
                $entityRequest->online = $request->online;
            }
            $entityRequest->coordonnees = $request->coordonnees;
            $entityRequest->dateAdd = $request->dateAdd;
            $entityRequest->dateUpdate = $request->dateUpdate;
            $entityRequest->dateDelete = $request->dateDelete;
            if (is_null($entityRequest->id)) {
                $message->idActivite = $this->getEntity()->addRow($entityRequest);
            } else {
                if (isset($request->moveUp)) {
                    $entityRequest->moveUp = $request->moveUp;
                }
                if (isset($request->show)) {
                    $entityRequest->show = $request->show;
                }
                $this->getEntity()->updateRow($entityRequest);
            }
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header           $header
     * @param Llv_Services_Activity_Filter_Activity $filter
     *
     * @return Llv_Services_Activity_Message_Activity
     */
    public function deleteRow(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Filter_Activity $filter
    )
    {
        $message = new Llv_Services_Activity_Message_Activity();
        try {
            $entityFilter = new Llv_Entity_Activity_Filter_Activity();
            $entityFilter->id = $filter->id;
            $this->getEntity()->deleteRow($entityFilter);
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /** ••••••••••••••••••••••••••••••••••••••••••••••••••••••• */

    /**
     * @param Llv_Services_Message_Header           $header
     * @param Llv_Services_Activity_Filter_Activity $request
     *
     * @return Llv_Services_Activity_Message_Activity
     */
    public function getRowContent(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Filter_Activity $request
    )
    {
        $message = new Llv_Services_Activity_Message_Activity();
        try {
            $entityRequest = new Llv_Entity_Activity_Filter_Activity();
            $entityRequest->id = $request->id;
            $entityRequest->idLangue = $request->idLangue;
            if (!is_null($entityRequest->id) && !is_null($entityRequest->idLangue)) {
                $this->getEntity()->getRowContent($entityRequest);
            }
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header               $header
     * @param Llv_Services_Activity_Request_EditContent $request
     *
     * @return Llv_Services_Activity_Message_Activity
     */
    public function editRowContent(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Request_EditContent $request
    )
    {
        $message = new Llv_Services_Activity_Message_Activity();
        try {
            $entityRequest = new Llv_Entity_Activity_Request_EditContent();
            $entityRequest->idActivity = isset($request->idActivity)
                && !is_null($request->idActivity)
                && !empty($request->idActivity)
                ? $request->idActivity
                : null;
            $entityRequest->idLangue = $request->idLangue;
            $entityRequest->lien = stripslashes($request->lien);
            $entityRequest->content = stripslashes($request->content);
            $entityRequest->title = $request->title;

            $filter = new Llv_Services_Activity_Filter_Activity();
            $filter->id = $request->idActivity;
            $filter->idLangue = $request->idLangue;
            $result = $this->getOne($header, $filter);

            if (!$result->success) {
                $this->getEntity()->addRowContent($entityRequest);
            } else {
                $this->getEntity()->updateRowContent($entityRequest);
            }
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /** ••••••••••••••••••••••••••••••••••••••••••••••••••••••• */
    /**
     * @param Llv_Services_Message_Header        $header
     * @param Llv_Services_Activity_Request_File $request
     *
     * @return Llv_Services_Activity_Message_File
     */
    public function addRowFile(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Request_File $request
    )
    {
        $message = new Llv_Services_Activity_Message_File();
        try {
            $file = new Llv_Dto_File();
            $file->filename = $request->filename;
            $file->tmpName = $request->tmpName;
            $file->error = $request->error;
            if ($file->error == UPLOAD_ERR_OK) {
                $filename = $this->getUploaderEntity()->moveFile(
                    $file,
                    Llv_Services_Activity_Helper_Activity::getActivityFilesPath()
                );
                if (!is_null($filename)) {
                    $entityFilter = new Llv_Entity_Activity_Request_File();
                    $entityFilter->idActivity = $request->idActivity;
                    $entityFilter->filename = $filename;
                    $entityFilter->originalFilename = $request->filename;
                    $entityFilter->mimeType = $request->mimeType;
                    $entityFilter->size = $request->size;
                    $entityFilter->dateAdd = new DateTime();
                    $entityFilter->dateDelete = null;
                    if (!is_null($this->getEntity()->addRowFile($entityFilter))) {
                        $message->success = true;
                    }
                }
                if (!$message->success) {
                    unlink(Llv_Services_Activity_Helper_Activity::getActivityFilesPath() . $filename);
                }
            }
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header       $header
     * @param Llv_Services_Activity_Filter_File $filter
     *
     * @return Llv_Services_Activity_Message_File
     */
    public function getActivityFiles(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Filter_File $filter
    )
    {
        $message = new Llv_Services_Activity_Message_File();
        try {
            /** On ajoute les illustrations */
            $entityFilter = new Llv_Entity_Activity_Filter_File();
            $entityFilter->idActivity = $filter->idActivity;
            $message->files = $this->getEntity()->getActivityFiles($entityFilter);
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header        $header
     * @param Llv_Services_Activity_Request_File $request
     *
     * @return Llv_Services_Activity_Message_File
     */
    public function updateRowFile(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Request_File $request
    )
    {
        $message = new Llv_Services_Activity_Message_File();
        try {
            /** On ajoute les illustrations */
            $entityRequest = new Llv_Entity_Activity_Request_File();
            $entityRequest->id = $request->id;
            if (isset($request->moveUp)) {
                $entityRequest->moveUp = $request->moveUp;
            }
            if (isset($request->show)) {
                $entityRequest->show = $request->show;
            }
            $message->file = $this->getEntity()->updateRowFile($entityRequest);
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header        $header
     * @param Llv_Services_Activity_Request_File $filter
     *
     * @return Llv_Services_Activity_Message_File
     */
    public function deleteRowFile(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Request_File $filter
    )
    {
        $message = new Llv_Services_Activity_Message_File();
        try {
            /** On ajoute les illustrations */
            $entityFilter = new Llv_Entity_Activity_Filter_File();
            $entityFilter->id = $filter->id;
            $message->idActivite = $this->getEntity()->deleteRowFile($entityFilter);
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /** ••••••••••••••••••••••••••••••••••••••••••••••••••••••• */

    /**
     * @param Llv_Services_Message_Header           $header
     * @param Llv_Services_Activity_Filter_Category $filter
     *
     * @return Llv_Services_Activity_Message_Category
     */
    public function categoryGetOne(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Filter_Category $filter
    )
    {
        $message = new Llv_Services_Activity_Message_Category();
        try {
            $entityFilter = new Llv_Entity_Activity_Filter_Category();
            $entityFilter->id = $filter->id;
            $message->categorie = $this->getEntity()->categoryGetOne($entityFilter);
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }

    /**
     * @param Llv_Services_Message_Header           $header
     * @param Llv_Services_Activity_Filter_Category $filter
     *
     * @return Llv_Services_Activity_Message_Category
     */
    public function categoryGetAll(
        Llv_Services_Message_Header $header,
        Llv_Services_Activity_Filter_Category $filter
    )
    {
        $message = new Llv_Services_Activity_Message_Category();
        try {
            $entityFilter = new Llv_Entity_Activity_Filter_Category();
            if (isset($filter->online)) {
                $entityFilter->online = $filter->online;
            }
            $entityFilter->idLangue = $filter->idLangue;
            $message->categories = $this->getEntity()->categoryGetAll($entityFilter);
            $message->success = true;
        } catch (Exception $e) {
            $message->errorList[] = $e;
        }
        return $message;
    }
}