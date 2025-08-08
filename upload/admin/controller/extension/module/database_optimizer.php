<?php
class ControllerExtensionModuleDatabaseOptimizer extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/module/database_optimizer');
        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/database_optimizer', 'user_token=' . $this->session->data['user_token'], true)
        );

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action_optimize'] = $this->url->link('extension/module/database_optimizer/optimize', 'user_token=' . $this->session->data['user_token'], true);
        $data['action_clear_sessions'] = $this->url->link('extension/module/database_optimizer/clearSessions', 'user_token=' . $this->session->data['user_token'], true);
        $data['action_clear_cache'] = $this->url->link('extension/module/database_optimizer/clearCache', 'user_token=' . $this->session->data['user_token'], true);
        $data['action_check_tables'] = $this->url->link('extension/module/database_optimizer/checkTables', 'user_token=' . $this->session->data['user_token'], true);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/database_optimizer', $data));
    }

    public function optimize() {
        $this->load->language('extension/module/database_optimizer');
        $this->load->model('extension/module/database_optimizer');
        $optimized_tables = $this->model_extension_module_database_optimizer->optimizeTables();
        $this->session->data['success'] = $this->language->get('text_success_optimize') . ' (' . implode(', ', $optimized_tables) . ')';
        $this->response->redirect($this->url->link('extension/module/database_optimizer', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function clearSessions() {
        $this->load->language('extension/module/database_optimizer');
        $this->load->model('extension/module/database_optimizer');
        $this->model_extension_module_database_optimizer->clearSessions();
        $this->session->data['success'] = $this->language->get('text_success_sessions');
        $this->response->redirect($this->url->link('extension/module/database_optimizer', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function clearCache() {
        $this->load->language('extension/module/database_optimizer');
        $this->load->model('extension/module/database_optimizer');
        $this->model_extension_module_database_optimizer->clearCache();
        $this->session->data['success'] = $this->language->get('text_success_cache');
        $this->response->redirect($this->url->link('extension/module/database_optimizer', 'user_token=' . $this->session->data['user_token'], true));
    }

    public function checkTables() {
        $this->load->language('extension/module/database_optimizer');
        $this->load->model('extension/module/database_optimizer');
        $results = $this->model_extension_module_database_optimizer->checkTables();
        $this->session->data['success'] = $this->language->get('text_success_check_tables');
        $this->session->data['table_results'] = $results; // ذخیره نتایج برای نمایش
        $this->response->redirect($this->url->link('extension/module/database_optimizer', 'user_token=' . $this->session->data['user_token'], true));
    }
}