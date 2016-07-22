<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Installer_model
 *
 * Model do sistema de Instalação
 *
 * @package      Installer
 * @author       Natan Felles <natanfelles@gmail.com>
 */
class Installer_model extends CI_Model {

	/**
	 * @var array $attributes Atributos obrigatórios das tabelas
	 */
	protected $attributes = array(
		'ENGINE'        => 'InnoDB',
		'CHARACTER SET' => 'utf8',
		'COLLATE'       => 'utf8_general_ci',
	);

	/**
	 * @see Installer_model::table_users()
	 * @var string $table_news Nome da Tabela de Usuários
	 */
	protected $table_users = 'users';

	/**
	 * @see Installer_model::table_recover_passwords()
	 * @var string $table_news Nome da Tabela de Recuperação de Senhas
	 */
	protected $table_recover_passwords = 'recover_passwords';

	/**
	 * @see Installer_model::table_authors()
	 * @var string $table_news Nome da Tabela de Autores
	 */
	protected $table_authors = 'authors';

	/**
	 * @see Installer_model::table_images()
	 * @var string $table_news Nome da Tabela de Imagens
	 */
	protected $table_images = 'images';

	/**
	 * @see Installer_model::table_categories()
	 * @var string $table_categories Nome da Tabela de Categorias
	 */
	protected $table_categories = 'categories';

	/**
	 * @see Installer_model::table_subcategories()
	 * @var string $table_categories Nome da Tabela de Sub Categorias
	 */
	protected $table_subcategories = 'subcategories';

	/**
	 * @see Installer_model::table_news()
	 * @var string $table_news Nome da Tabela de Notícias
	 */
	protected $table_news = 'news';

	/**
	 * @see Installer_model::table_subcategories_to_news()
	 * @var string $table_subcategories_to_news Nome da Tabela de Relacionamento entre a Tabela de Sub Categorias com a Tabela de Notícias
	 */
	protected $table_subcategories_to_news = 'subcategories_to_news';

	/**
	 * @see Installer_model::table_weather()
	 * @var string $table_weather Nome da Tabela de Previsão do tempo
	 */
	protected $table_weather = 'weather';

	/**
	 * @see Installer_model::table_sessions()
	 * @var string $table_sessions Nome da Tabela de Sessões
	 */
	protected $table_sessions = 'sessions';

	/**
	 * @see Installer_model::table_ads()
	 * @var string $table_ads Nome da Tabela de Publicidades
	 */
	protected $table_ads = 'ads';

	/**
	 * @see Installer_model::table_subcategories_to_ads()
	 * @var string $table_subcategories_to_news Nome da Tabela de Relacionamento entre a Tabela de Sub Categorias com a Tabela de Publicidade
	 */
	protected $table_subcategories_to_ads = 'subcategories_to_ads';


	/**
	 * Installer_model constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->dbforge();
	}

	/**
	 * Cria as tabelas necessárias no banco de dados
	 *
	 * @return void
	 */
	public function create_tables()
	{
		$this->table_users();
		$this->table_recover_passwords();
		$this->table_authors();
		$this->table_images();
		$this->table_categories();
		$this->table_subcategories();
		$this->table_news();
		$this->table_subcategories_to_news();
		$this->table_weather();
		$this->table_sessions();
		$this->table_ads();
		$this->table_subcategories_to_ads();
	}

	/**
	 * Tabela de Usuários
	 *
	 * Usada para autenticação
	 *
	 * @see Installer_model::$table_user
	 * @return void
	 */
	protected function table_users()
	{
		$table = $this->table_users;

		$fields = array(
			'user_id'             => array(
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE,
			),
			'user_username'       => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'user_email'          => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'user_email_optional' => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'user_password'       => array(
				'type' => 'TEXT',
			),
			'user_lastusernames'  => array(
				'type'    => 'TEXT',
				'comment' => 'Últimos username codificados em JSON',
			),
			'user_lastpasswords'  => array(
				'type'    => 'TEXT',
				'comment' => 'Últimas senhas codificadas em JSON',
			),
		);
		$this->dbforge->add_key('user_id', TRUE);
		$this->dbforge->add_field($fields);

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela para Recuperação de Senhas
	 *
	 * @todo Código deve ser chamado pela uri de recuperação. Deve haver controle de segurança na quantidade de tentativas/códigos inexistentes
	 *
	 * @see  Installer_model::$recover_passwords
	 * @return void
	 */
	protected function table_recover_passwords()
	{
		$table = $this->table_recover_passwords;

		$fields = array(
			'recover_password_code'      => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'recover_password_timestamp' => array(
				'type' => 'TIMESTAMP',
			),
			'user_id'                    => array(
				'type'     => 'INT',
				'unsigned' => TRUE,
			),
		);
		$this->dbforge->add_key('recover_password_code', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (user_id) REFERENCES {$this->table_users}(user_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Autores
	 *
	 * @see Installer_model::$table_authors
	 * @return void
	 */
	protected function table_authors()
	{
		$table = $this->table_authors;

		$fields = array(
			'author_id'              => array(
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE,
			),
			'author_uri'             => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'author_firstname'       => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'author_lastname'        => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'author_photo_uri'       => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'author_bio_description' => array(
				'type' => 'TEXT',
			),
			'author_social_networks' => array(
				'type'    => 'TEXT',
				'comment' => 'Redes sociais codificadas em JSON',
			),
			'user_id'                => array(
				'type'     => 'INT',
				'unsigned' => TRUE,
			),
		);
		$this->dbforge->add_key('author_id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (user_id) REFERENCES {$this->table_users}(user_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Imagens
	 *
	 * @see Installer_model::$table_images
	 * @return void
	 */
	protected function table_images()
	{
		$table = $this->table_images;

		$fields = array(
			'image_id'          => array(
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE,
			),
			'image_uri'         => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'image_author'      => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'image_datetime'    => array(
				'type'    => 'DATETIME',
				'default' => '0000-00-00 00:00:00',
			),
			'image_description' => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
		);
		$this->dbforge->add_key('image_id', TRUE);
		$this->dbforge->add_key('image_uri');
		$this->dbforge->add_field($fields);

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Categorias
	 *
	 * 1º Segmento da URL
	 *
	 * @see Installer_model::$table_categories
	 * @return void
	 */
	protected function table_categories()
	{
		$table = $this->table_categories;

		$fields = array(
			'category_uri'         => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'category_title'       => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'category_description' => array(
				'type' => 'TEXT',
			),
			'category_color'       => array(
				'type'       => 'VARCHAR',
				'constraint' => '6',
				'unique'     => TRUE,
			),
		);
		$this->dbforge->add_key('uri', TRUE);
		$this->dbforge->add_field($fields);

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Sub Categorias
	 *
	 * 2º Segmento da URL
	 *
	 * @see Installer_model::$table_subcategories
	 * @return void
	 */
	protected function table_subcategories()
	{
		$table = $this->table_subcategories;

		$fields = array(
			'category_uri'            => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'subcategory_uri'         => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'subcategory_title'       => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'subcategory_description' => array(
				'type' => 'TEXT',
			),
		);
		$this->dbforge->add_key('category_uri');
		$this->dbforge->add_key('subcategory_uri', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (category_uri) REFERENCES {$this->table_categories}(category_uri) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Notícias
	 *
	 * 3º Segmento da URL
	 *
	 * @see Installer_model::$table_news
	 * @return void
	 */
	protected function table_news()
	{
		$table = $this->table_news;

		$fields = array(
			'subcategory_uri'        => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'comment'    => 'Categoria principal da notícia. A qual será usada no 2º segmento da URL. Motivo: Ranking no Google - não repetir conteúdo',
			),
			'news_uri'               => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'news_title'             => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'unique'     => TRUE,
			),
			'news_description'       => array(
				'type' => 'TEXT',
			),
			'news_content'           => array(
				'type' => 'TEXT',
			),
			'news_creation_datetime' => array(
				'type'    => 'DATETIME',
				'default' => '0000-00-00 00:00:00',
			),
			'news_update_datetime'   => array(
				'type'    => 'DATETIME',
				'default' => '0000-00-00 00:00:00',
			),
			'news_active'            => array(
				'type'    => "ENUM('y','n')",
				'default' => 'y',
			),
			'news_show_author'       => array(
				'type'    => "ENUM('y','n')",
				'default' => 'n',
			),
			'author_id'              => array(
				'type'     => 'INT',
				'unsigned' => TRUE,
			),
			'image_id'               => array(
				'type'     => 'INT',
				'unsigned' => TRUE,
			),
		);
		$this->dbforge->add_key('subcategory_uri');
		$this->dbforge->add_key('uri', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (subcategory_uri) REFERENCES {$this->table_subcategories}(subcategory_uri) ON DELETE CASCADE ON UPDATE CASCADE");
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (author_id) REFERENCES {$this->table_authors}(author_id) ON DELETE CASCADE ON UPDATE CASCADE");
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (image_id) REFERENCES {$this->table_images}(image_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}


	/**
	 * Tabela de Relacionamento entre a Tabela de Sub Categorias com a Tabela de Notícias
	 *
	 * Notícia pode ter mais de uma Sub Categoria, mas só uma URL final
	 *
	 * @see Installer_model::$table_subcategories_to_news
	 * @see Installer_model::table_subcategories()
	 * @see Installer_model::table_news()
	 * @return void
	 */
	protected function table_subcategories_to_news()
	{
		$table = $this->table_subcategories_to_news;

		$fields = array(
			'subcategory_uri' => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'news_uri'        => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
		);
		$this->dbforge->add_key('subcategory_uri', TRUE);
		$this->dbforge->add_key('news_uri');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (subcategory_uri) REFERENCES {$this->table_subcategories}(subcategory_uri) ON DELETE CASCADE ON UPDATE CASCADE");
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (news_uri) REFERENCES {$this->table_news}(news_uri) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Previsão do Tempo
	 *
	 * @todo Deve ser gerenciada periodicamente sendo chamada pelo cron
	 *
	 * Dados coletados em http://forecast.io
	 *
	 * @see  Installer_model::$table_weather
	 * @see  https://developer.forecast.io/docs/v2
	 * @return void
	 */
	protected function table_weather()
	{
		$table = $this->table_weather;

		$fields = array(
			'weather_timestamp'    => array(
				'type' => 'TIMESTAMP',
			),
			'weather_temp_min'     => array(
				'type'       => 'INT',
				'constraint' => 2,
			),
			'weather_temp_max'     => array(
				'type'       => 'INT',
				'constraint' => 2,
			),
			'weather_temp_now'     => array(
				'type'       => 'INT',
				'constraint' => 2,
			),
			'weather_cloud_cover'  => array(
				'type'       => 'INT',
				'constraint' => 3,
				'unsigned'   => TRUE,
			),
			'weather_humidity'     => array(
				'type'       => 'INT',
				'constraint' => 3,
				'unsigned'   => TRUE,
			),
			'weather_wind_speed'   => array(
				'type'       => 'INT',
				'constraint' => 3,
				'unsigned'   => TRUE,
			),
			'weather_wind_bearing' => array(
				'type' => "ENUM('N','NE','E','SE','S','SW','W','NW')",
			),
			'weather_pressure'     => array(
				'type'     => 'DECIMAL(6,2)',
				'unsigned' => TRUE,
			),
			'weather_summary'      => array(
				'type' => "ENUM('clear-day','clear-night','rain','snow','sleet','wind','fog','cloudy','partly-cloudy-day','partly-cloudy-night','hail','thunderstorm',' tornado')",
			),
		);
		$this->dbforge->add_key('weather_timestamp', TRUE);
		$this->dbforge->add_field($fields);

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Sessões
	 *
	 * @see Installer_model::$table_sessions
	 * @return void
	 */
	protected function table_sessions()
	{
		$table = $this->table_sessions;

		$fields = array(
			'session_id'               => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'session_type'             => array(
				'type' => "ENUM('user','guest')",
			),
			'session_ip'               => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'session_country'          => array(
				'type'       => 'CHAR',
				'constraint' => 3,
			),
			'session_region'           => array(
				'type'       => 'CHAR',
				'constraint' => 2,
			),
			'session_city'             => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'session_latitude'         => array(
				'type' => 'DECIMAL(10, 8)',
			),
			'session_longitude'        => array(
				'type' => 'DECIMAL(10, 8)',
			),
			'session_datetime'         => array(
				'type'    => 'DATETIME',
				'default' => '0000-00-00 00:00:00',
			),
			'session_operating_system' => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'session_browser'          => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'comment'    => 'User Agent',
			),
			'session_current_uri'      => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'null'       => TRUE,
			),
			'user_id'                  => array(
				'type'     => 'INT',
				'unsigned' => TRUE,
				'null'     => TRUE,
			),
		);
		$this->dbforge->add_key('session_id', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (user_id) REFERENCES {$this->table_users}(user_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Publicidade
	 *
	 * @see Installer_model::$table_ads
	 * @return void
	 */
	protected function table_ads()
	{
		$table = $this->table_ads;

		$fields = array(
			'ad_id'        => array(
				'type'           => 'INT',
				'unsigned'       => TRUE,
				'auto_increment' => TRUE,
			),
			'ad_position'  => array(
				'type' => "ENUM('header','sidebar_top','sidebar_middle','content_bottom')",
			),
			'ad_period'    => array(
				'type' => "ENUM('dawn','morning','afternoon','night')",
			),
			'ad_image_uri' => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'ad_active'    => array(
				'type'    => "ENUM('y','n')",
				'default' => 'n',
			),
			'user_id'      => array(
				'type'     => 'INT',
				'unsigned' => TRUE,
			),
		);
		$this->dbforge->add_key('ad_id', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (user_id) REFERENCES {$this->table_users}(user_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Relacionamento entre a Tabela de Sub Categorias com a Tabela de Publicidade
	 *
	 * Publicidade pode ter várias subcategorias. Mas apenas um período/horário.
	 *
	 * @see Installer_model::$table_subcategories_to_ads
	 * @see Installer_model::table_subcategories()
	 * @see Installer_model::table_ads()
	 * @return void
	 */
	protected function table_subcategories_to_ads()
	{
		$table = $this->table_subcategories_to_ads;

		$fields = array(
			'subcategory_uri' => array(
				'type'       => 'VARCHAR',
				'constraint' => 255,
			),
			'ad_id'           => array(
				'type'     => 'INT',
				'unsigned' => TRUE,
			),
		);
		$this->dbforge->add_key('subcategory_uri', TRUE);
		$this->dbforge->add_key('ad_id');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (subcategory_uri) REFERENCES {$this->table_subcategories}(subcategory_uri) ON DELETE CASCADE ON UPDATE CASCADE");
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (ad_id) REFERENCES {$this->table_ads}(ad_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

}
