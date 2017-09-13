<?php
// declare(strict_types = 1);
namespace Nooper;

class Menu {
	
	/**
	 * Constants
	 */
	const click_custom = 'click';
	const click_scan_push = 'scancode_push';
	const click_scan_message = 'scancode_waitmsg';
	const click_camera = 'pic_sysphoto';
	const click_camera_or_photo = 'pic_photo_or_album';
	const click_app_photo = 'pic_weixin';
	const click_location = 'location_select';
	const media_id = 'media_id';
	const media_limited_id = 'view_limited';
	
	/**
	 * Properties
	 */
	protected $token;
	protected $menus = [];
	protected $operate_urls = ['create'=>'https://api.weixin.qq.com/cgi-bin/menu/create', 'delete'=>'https://api.weixin.qq.com/cgi-bin/menu/delete', 'get'=>'https://api.weixin.qq.com/cgi-bin/menu/get'];
	
	/**
	 * public void function __construct(string $token = null)
	 */
	public function __construct(string $access_token = null) {
		$this->token = $access_token ?? (new Token())->read();
	}
	
	/**
	 * public void function __destruct(void)
	 */
	function __destruct() {
		// echo '- end -';
	}
	
	/**
	 * public ?array function get(void)
	 */
	public function get(): array {
		$mmc = new Mimicry();
		$helper = new Translator();
		$qry_params = ['access_token'=>$this->token];
		try{
			$ends = $helper->parse_json($mmc->get($this->operate_urls['get'], $qry_params));
		}catch(\Exception $err){
			return null;
		}
		return isset($ends['menu']) ? $ends : null;
	}
	
	/**
	 * public boolean function create(void)
	 */
	public function create(): bool {
		$mmc = new Mimicry();
		$helper = new Translator();
		$json = $helper->create_json(['button'=>$this->menus]);
		if(is_string($json)){
			$qry_params = ['access_token'=>$this->token];
			try{
				$ends = $helper->parse_json($mmc->post($this->urls['create'], $json, $qry_params));
			}catch(\Exception $err){
				return false;
			}
			return isset($ends['errcode']) && 0 == $ends['errcode'] ? true : false;
		}
		return false;
	}
	
	/**
	 * public boolean function delete(void)
	 */
	public function delete(): bool {
		$mmc = new Mimicry();
		$helper = new Translator();
		$qry_params = ['access_token'=>$this->token];
		try{
			$ends = $helper->parse_json($mmc->get($this->urls['delete'], $qry_params));
		}catch(\Exception $err){
			return false;
		}
		return isset($ends['errcode']) && 0 == $ends['errcode'] ? true : false;
	}
	
	/**
	 * public array function create_view(string $name, string $url)
	 */
	public function create_view(string $name, string $url): array {
		return ['type'=>'view', 'name'=>$name, 'url'=>$url];
	}
	
	/**
	 * public array function create_program(string $name, string $url, string $app_id, string $page_path)
	 */
	public function create_program(string $name, string $url, string $app_id, string $page_path): array {
		return ['type'=>'miniprogram', 'name'=>$name, 'url'=>$url, 'appid'=>$app_id, 'pagepath'=>$page_path];
	}
	
	/**
	 * public array function create_click(string $name, string $key, string $type = self::click_custom)
	 */
	public function create_click(string $name, string $key, string $type = self::click_custom): array {
		return ['type'=>$type, 'name'=>$name, 'key'=>$key];
	}
	
	/**
	 * public array create_media(string $name, string $id, string $type = self::media_id)
	 */
	public function create_media(string $name, string $id, string $type = self::media_id): array {
		return ['type'=>$type, 'name'=>$name, 'media_id'=>$id];
	}
	
	/**
	 * public array function create_menu_group(string $name)
	 */
	public function create_menu_group(string $name): array {
		return ['name'=>$name, 'sub_button'=>[]];
	}
	
	/**
	 * public function add_child_menu(array &$menu_group, array $menu)
	 */
	public function add_child_menu(array &$menu_group, array $menu): array {
		$menu_group['sub_button'][] = $menu;
		return $menu_group;
	}
	
	/**
	 * public array function add_menu(array $menu)
	 */
	public function add_menu(array $menu): array {
		$this->menus[] = $menu;
		return $this->menus;
	}
	
	/**
	 * public array function get_prepare_menus(void)
	 */
	public function get_prepare_menus(): array {
		return $this->menus;
	}
	//
}

