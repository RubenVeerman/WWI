<?php
namespace App\Enum;

/**
 * Class Navigation Helper
 * To help other class with the navigation bar.
 * @since 15-10-2017
 * @version 1.0
 * @author R Haan
 */
class Navigations
{
	protected const HOME_PAGE = "/home";

	protected const ARTICLE_PAGE = "/article/index";
	protected const ARTICLE_ADD_PAGE = "/article/add";
	protected const ARTICLE_OVERVIEW_PAGE = "/article/overview";

	protected const REGISTER_PAGE = "/register";
	protected const ADMIN_PAGE = "/admin";

	protected const CONTACT_PAGE = "/contact";

	protected const USER_PAGE = "/user";

	protected const ROOT_DIRECTORY = "../";

	public const LOCALHOST_FILEPATH = "/wideworldimporters/public/";

	#region SQL-databases
	protected const SITE_USERS_TABLE = "people";

	protected const SITE_CONTENT_TABLE = "content";
	#endregion SQL-databases
}

?>