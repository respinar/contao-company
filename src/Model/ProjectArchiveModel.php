<?php

declare(strict_types=1);

/*
 * This file is part of Contao Company Bundle.
 *
 * (c) Hamid Peywasti
 *
 * @license MIT
 */

namespace Respinar\CompanyBundle\Model;

use Contao\Model;
use Contao\Model\Collection;

/**
 * Reads and writes project archives.
 *
 * @property int               $id
 * @property int               $tstamp
 * @property string            $title
 * @property int               $jumpTo
 * @property int               $overviewPage
 * @property bool              $protected
 * @property string|array|null $groups
 *
 * @method static ProjectArchiveModel|null             findById($id, array $opt=array())
 * @method static ProjectArchiveModel|null             findByPk($id, array $opt=array())
 * @method static ProjectArchiveModel|null             findOneBy($col, $val, array $opt=array())
 * @method static Collection<ProjectArchiveModel>|null findBy($col, $val, array $opt=array())
 * @method static Collection<ProjectArchiveModel>|null findAll(array $opt=array())
 */
class ProjectArchiveModel extends Model
{
    protected static $strTable = 'tl_company_project_archive';
}
