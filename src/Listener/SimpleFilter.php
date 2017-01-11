<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2017                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */
namespace Civi\FlexMailer\Listener;

use Civi\Core\Resolver;
use Civi\FlexMailer\Event\ComposeBatchEvent;
use Civi\FlexMailer\FlexMailerTask;

/**
 * Class SimpleFilter
 * @package Civi\FlexMailer\Listener
 *
 * Provides a slightly sugary utility for writing a filter
 * that applies to email content.
 *
 * Note: This class is not currently used within org.civicrm.flexmailer, but
 * it ma ybe used by other extensions.
 */
class SimpleFilter {

  /**
   * Apply a filter function to a property of each email message.
   *
   * @param \Civi\FlexMailer\Event\ComposeBatchEvent $e
   * @param string $field
   *   The name of a MailParam field.
   * @param mixed $filter
   *   Function($value, FlexMailerTask $task, ComposeBatchEvent $e).
   *   The function returns a filtered value.
   * @throws \CRM_Core_Exception
   * @see \CRM_Utils_Hook::alterMailParams
   */
  public static function apply(ComposeBatchEvent $e, $field, $filter) {
    foreach ($e->getTasks() as $task) {
      /** @var FlexMailerTask $task */
      $value = $task->getMailParam($field);
      if ($value !== NULL) {
        $task->setMailParam($field, call_user_func($filter, $value, $task, $e));
      }
    }
  }

}
