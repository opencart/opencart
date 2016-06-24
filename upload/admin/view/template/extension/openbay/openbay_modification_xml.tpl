<modification>
	<name>OpenBay Pro menu</name>
	<version>2.2</version>
	<link>www.openbaypro.com</link>
	<author>EcommerceHQ</author>
	<code>openbaypro_menu</code>

	<file path="admin/controller/common/column_left.php">
		<operation>
			<search><![CDATA[// Stats]]></search>
			<add position="before"><![CDATA[
		// OpenBay Pro Menu
		$openbay_menu = array();

		//if ($this->user->hasPermission('access', 'extension/openbay')) {
			$openbay_menu[] = array(
				'name'	   => $this->language->get('text_dashboard'),
				'href'     => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], true),
				'children' => array()
			);

			$openbay_menu[] = array(
				'name'	   => $this->language->get('text_manage'),
				'href'     => $this->url->link('extension/openbay/manage', 'token=' . $this->session->data['token'], true),
				'children' => array()
			);
		//}

		if ($openbay_menu) {
			$data['menus'][] = array(
				'id'       => 'menu-design',
				'icon'	   => 'fa-television',
				'name'	   => $this->language->get('text_openbay_extension'),
				'href'     => '',
				'children' => $openbay_menu
			);
		}
		]]></add>
		</operation>
	</file>
</modification>