<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Preview\Understand\Assistant\Task;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 *
 * @property string $accountSid
 * @property string $assistantSid
 * @property string $taskSid
 * @property string $url
 * @property array $data
 */
class TaskActionsInstance extends InstanceResource {
    /**
     * Initialize the TaskActionsInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $assistantSid The unique ID of the parent Assistant.
     * @param string $taskSid The unique ID of the Task.
     */
    public function __construct(Version $version, array $payload, string $assistantSid, string $taskSid) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'assistantSid' => Values::array_get($payload, 'assistant_sid'),
            'taskSid' => Values::array_get($payload, 'task_sid'),
            'url' => Values::array_get($payload, 'url'),
            'data' => Values::array_get($payload, 'data'),
        ];

        $this->solution = ['assistantSid' => $assistantSid, 'taskSid' => $taskSid, ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return TaskActionsContext Context for this TaskActionsInstance
     */
    protected function proxy(): TaskActionsContext {
        if (!$this->context) {
            $this->context = new TaskActionsContext(
                $this->version,
                $this->solution['assistantSid'],
                $this->solution['taskSid']
            );
        }

        return $this->context;
    }

    /**
     * Fetch the TaskActionsInstance
     *
     * @return TaskActionsInstance Fetched TaskActionsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): TaskActionsInstance {
        return $this->proxy()->fetch();
    }

    /**
     * Update the TaskActionsInstance
     *
     * @param array|Options $options Optional Arguments
     * @return TaskActionsInstance Updated TaskActionsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(array $options = []): TaskActionsInstance {
        return $this->proxy()->update($options);
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name) {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Preview.Understand.TaskActionsInstance ' . \implode(' ', $context) . ']';
    }
}