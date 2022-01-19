<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\Control\HTTPRequest;

    class PageController extends ContentController
    {
        /**
         * An array of actions that can be accessed via a request. Each array element should be an action name, and the
         * permissions or conditions required to allow the user to access it.
         *
         * <code>
         * [
         *     'action', // anyone can access this action
         *     'action' => true, // same as above
         *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
         *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
         * ];
         * </code>
         *
         * @var array
         */
        private static $allowed_actions = [
            "autocomplete"
        ];

        protected function init()
        {
            parent::init();
            // You can include any CSS or JS required by your project here.
            // See: https://docs.silverstripe.org/en/developer_guides/templates/requirements/
        }

        public function autocomplete(HTTPRequest $request)
        {
            // get query from the request 
            $query = $request->getVars()["query"];
            if (!isset($query)) return null;

            // get the formatted results ready to display on front end
            $results_api = new Results($query);
            $data_api = $results_api->getData();
        }
    }
}
