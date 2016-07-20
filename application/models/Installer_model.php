<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Installer_model extends CI_Controller {

	/**
	 * @var array $attributes Atributos obrigatórios das tabelas
	 */
	protected $attributes = array(
		'ENGINE'        => 'InnoDB',
		'CHARACTER SET' => 'utf8',
		'COLLATE'       => 'utf8_general_ci',
	);

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
	 * @see Installer_model::table_authors()
	 * @var string $table_news Nome da Tabela de Autores
	 */
	protected $table_authors = 'authors';

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
	 * @return void
	 */
	public function create_tables()
	{
		$this->table_authors();
		$this->table_categories();
		$this->table_subcategories();
		$this->table_news();
		$this->table_subcategories_to_news();
	}

	/**
	 * Tabela de Autores
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
				'comment'  => 'Todo: Relacionar',
			),
		);
		$this->dbforge->add_key('author_id', TRUE);
		$this->dbforge->add_key('user_id');
		$this->dbforge->add_field($fields);
		// Todo: Relacionar
		//$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (user_id) REFERENCES {$this->table_users}(user_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}

	/**
	 * Tabela de Categorias
	 * 1º Segmento da URL
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
	 * 2º Segmento da URL
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
	 * 3º Segmento da URL
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
				'comment'  => 'Todo: Relacionar',
			),
		);
		$this->dbforge->add_key('subcategory_uri');
		$this->dbforge->add_key('uri', TRUE);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (subcategory_uri) REFERENCES {$this->table_subcategories}(subcategory_uri) ON DELETE CASCADE ON UPDATE CASCADE");
		$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (author_id) REFERENCES {$this->table_authors}(author_id) ON DELETE CASCADE ON UPDATE CASCADE");
		// Todo: Relacionar
		//$this->dbforge->add_field("CONSTRAINT FOREIGN KEY (image_id) REFERENCES {$this->table_images}(image_id) ON DELETE CASCADE ON UPDATE CASCADE");

		$this->dbforge->create_table($table, TRUE, $this->attributes);
	}


	/**
	 * Tabela de Relacionamento entre a Tabela de Sub Categorias com a Tabela de Notícias
	 * Notícia pode ter mais de uma Sub Categoria, mas só uma URL final
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

}