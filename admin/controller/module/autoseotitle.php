<?php
class ControllerModuleAutoSeoTitle extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('module/autoseotitle');
		$this->document->setTitle($this->language->get('ast_title'));
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('autoseotitle', $this->request->post);		
			$this->session->data['success'] = $this->language->get('ast_success_text');
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['error']) ) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
	
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('ast_title'),
			'href'      => $this->url->link('module/autoseotitle', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['button_save'] = $this->language->get('ast_save');
		$this->data['button_cancel'] = $this->language->get('ast_cancel');

		if (isset($this->request->post['ast_sitename'])) {
			$this->data['ast_sitename'] = $this->request->post['ast_sitename'];
		} else {
			$this->data['ast_sitename'] = $this->config->get('ast_sitename');
		}

		if (isset($this->request->post['ast_price'])) {
			$this->data['ast_price'] = $this->request->post['ast_price'];
		} else {
			$this->data['ast_price'] = $this->config->get('ast_price');
		}
		
		if (isset($this->request->post['ast_special'])) {
			$this->data['ast_special'] = $this->request->post['ast_special'];
		} else {
			$this->data['ast_special'] = $this->config->get('ast_special');
		}

		if (isset($this->request->post['ast_separator'])) {
			$this->data['ast_separator'] = $this->request->post['ast_separator'];
		} else {
			$this->data['ast_separator'] = $this->config->get('ast_separator');
		}

		$this->data['action'] = $this->url->link('module/autoseotitle', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['heading_title'] = $this->language->get('ast_title');
		$this->data['description'] = $this->language->get('ast_description');
		$this->data['entry_sitename'] = $this->language->get('ast_entry_sitename');
		$this->data['entry_sitename_description'] = $this->language->get('ast_entry_sitename_description');
		$this->data['entry_price'] = $this->language->get('ast_entry_price');
		$this->data['entry_price_description'] = $this->language->get('ast_entry_price_description');
		$this->data['entry_special'] = $this->language->get('ast_entry_special');
		$this->data['entry_special_description'] = $this->language->get('ast_entry_special_description');
		$this->data['entry_separator'] = $this->language->get('ast_entry_separator');
		$this->data['entry_separator_description'] = $this->language->get('ast_entry_separator_description');
		$this->data['yes'] = $this->language->get('ast_yes');
		$this->data['yes_after'] = $this->language->get('ast_yes_after');
		$this->data['yes_before'] = $this->language->get('ast_yes_before');
		$this->data['no'] = $this->language->get('ast_no');

		$this->data['modules'] = array();
		
		if (isset($this->request->post['autoseotitle_module'])) {
			$this->data['modules'] = $this->request->post['autoseotitle_module'];
		} else { 
			$this->data['modules'] = $this->config->get('autoseotitle_module');
		}

		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
						
		$this->template = 'module/autoseotitle.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());

	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/autoseotitle')) {
			$this->error['warning'] = $this->language->get('ast_error_access');
		}
		
		if (isset($this->request->post['autoseotitle_module'])) {
			foreach ($this->request->post['autoseotitle_module'] as $key => $value) {				
				
			}
		}	
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','autoseotitle','ast_sitename','b','0')");
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','autoseotitle','ast_price','y','0')");
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','autoseotitle','ast_special','y','0')");
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','autoseotitle','ast_separator','-','0')");
	}

	public function uninstall() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'ast_sitename'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'ast_price'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'ast_special'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'ast_separator'");
	}

}
?>
