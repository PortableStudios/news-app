<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\Control\HTTPRequest;
    use SilverStripe\View\ViewableData;

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

            // get the formatted results
            $results_api = new Results($query);
            $results_data = $results_api->getData();

            // create a viewable object and pass the results to be ready to show on front end
            $viewable_data = new ViewableData();
            return $viewable_data->customise([
                "Results" => $results_data
            ])->renderWith('Results');
        }
    }
}
