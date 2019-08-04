<?php

/**
 * Copyright 2019 Douglas Silva
 *
 * This file is part of Fragments.
 *
 * Fragments is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Fragments.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Fragments\Utility\Feedback;

use Fragments\Utility\Errors\SoftException;

/**
 * Feedback Utility
 *
 * Retrieves, formats and returns feedback messages. To create new
 * feedback types, a new <Type>Feedback class extending from Feedback
 * must be created, and it must contain two properties: $feedbackType (string)
 * and $feedbackText (array). An example for the ID (key) of a feedback
 * message: FEEDBACK_USERNAME_EMPTY. In addition, the styling must be written
 * at /public_html/css/style.css.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
abstract class Feedback
{
    private $feedbackID;

    private $type;

    /**
     * Sets the feedback ID and retrieves its type.
     *
     * @param string $feedbackID The identifier of a feedback message
     */
    public function __construct($feedbackID)
    {
        $this->feedbackID = $feedbackID;

        $this->type = $this->getType();
    }

    /**
     * Retrieves a feedback message.
     *
     * @throws SoftException
     * @return string
     */
    public function get()
    {
        $message = $this->feedbackText[$this->feedbackID];

        try {
            if (is_null($message)) {
                throw new SoftException($this->feedbackID);
            }
        } catch(SoftException $err) {
            $message = $err->invalidFeedbackID();
        }

        $message = $this->format($message);

        return $message;
    }

    private function getType()
    {
        return $this->feedbackType;
    }

    /**
     * Applies styling to a feedback message
     *
     * @param string $message
     * @return string
     */
    private function format($message)
    {
        ob_start();

        echo "<div class='alert alert-" . $this->type . "'>
            " . $message . "
            </div>";

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }
}

class DangerFeedback extends Feedback
{
    protected $feedbackType = 'danger';

    protected $feedbackText = array(
        'EXCEPTION_SESSION_EXPIRED' => 'This session has expired',
    );
}

class WarningFeedback extends Feedback
{
    protected $feedbackType = 'warning';

    protected $feedbackText = array(
        'FEEDBACK_USERNAME_EMPTY' => 'Username was left empty',
        'FEEDBACK_USERNAME_LENGTH' => 'Username must be longer than 3 and '.
            'shorter than 16 characters',
        'FEEDBACK_USERNAME_INVALID' => 'Username can only contain '.
            'alphanumerical characters(a-z, A-Z, 0-9) and underscore(_). '.
            'Two or more consecutive underscores(__) will be rejected.',
        'FEEDBACK_PASSWORD_EMPTY' => 'Password was left empty',
        'FEEDBACK_PASSWORD_LENGTH' => 'Minimum password length is 8 characters',
        'FEEDBACK_NOT_REGISTERED' => 'Invalid credentials',
        'FEEDBACK_INCORRECT_PASSWD' => 'Invalid credentials',
        'FEEDBACK_USERNAME_TAKEN' => 'Username already taken',
    );
}

class SuccessFeedback extends Feedback
{
    protected $feedbackType = 'success';

    protected $feedbackText = array(
        'FEEDBACK_REGISTRATION_COMPLETE' => 'Registration complete',
    );
}
