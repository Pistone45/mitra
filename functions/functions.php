<?php
ob_start();
session_start();
error_reporting(E_ALL);


require ( dirname (__FILE__) . '/../connection/connection.php'); 
date_default_timezone_set("Africa/Harare");

class User{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function login($username, $password){
		if(!empty($username) && !empty ($password)) {
			$status = 1; //active
		$login_query = $this->dbCon->prepare("SELECT username, firstname, lastname, phone, email, password FROM users WHERE username=? AND status=?" );
				$login_query->bindParam(1, $username);
				$login_query->bindParam(2, $status);
				$login_query->execute();
				
				if($login_query->rowCount() ==1){
				$row = $login_query -> fetch();
				$hash_pass =trim($row['password']);
				//verify password
				if (password_verify($password, $hash_pass)) {
					// Success!
					$_SESSION['user'] = $row;
					
					header("Location: view-news.php");
					//die();
				}
				else {
					 $_SESSION['invalidUser']=true;
				}
			
					
				}else{
                    
                    $_SESSION['invalidUser']=true;
					
				}
		
		}
	} //end of login authentication
	
	public function checkUser($uid,$pass){
		
		if(!empty($uid) && !empty ($pass)) {
		$login_query = $this->dbCon->prepare("SELECT uid, pass,name FROM click WHERE uid=? AND pass=?" );
				$login_query->bindParam(1, $uid);
				$login_query->bindParam(2, $pass);
				$login_query->execute();
				
				if($login_query->rowCount() ==1){
				$row = $login_query -> fetch();
				
				return $row;
				//verify password
			
				
					
				}
		
		}
	}
	public function getUserProfile(){	
				$getUserProfile = $this->dbCon->prepare("SELECT username, phone,email,firstname,middlename,lastname FROM users WHERE username=?" );
				$getUserProfile->bindParam(1, $_SESSION['user']['username']);
				$getUserProfile->execute();

				if($getUserProfile->rowCount() ==1){
				$row = $getUserProfile -> fetch();

				return $row;
				//verify password



				}

		
	}
  public function addUser($username,$firstname,$middlename, $lastname, $role,$password,$phone,$email){
	  $date = DATE("Y-m-d h:i");
		//check if the user is already in the system before adding new user
		$checkUser = $this->dbCon->prepare("SELECT username from users where username=?" );
		$checkUser->bindValue(1, $username);
		$checkUser->execute();
		if($checkUser->rowCount() ==1){
			//user already in the system
			$_SESSION['user_found']= true;
		}else{
				$addUser = $this->dbCon->prepare("INSERT INTO users (username, password, firstname,middlename, lastname,email,phone) 
				VALUES (:username, :password, :firstname,:middlename, :lastname,:email,:phone)" );
				$addUser->execute(array(
						  ':username'=>($username),
						  ':password'=>($password),
						  ':firstname'=>($firstname),
						  ':middlename'=>($middlename),
						  ':lastname'=>($lastname),
						  ':email'=>($email),
						  ':phone'=>($phone),
						  ));		

		  $_SESSION['user-added']=true;
			}
		

	} //end adding users

//edit user
	  public function editUser($username,$firstname,$middlename, $lastname, $role,$phone,$email,$status,$UID){
				//echo $username; die();	
				$editUser = $this->dbCon->prepare("UPDATE users SET firstname,middlename,lastname?,email=?,phone=?,role=?,status=? WHERE username=?" );
				$editUser->bindParam(1,$firstname);
				$editUser->bindParam(2,$middlename);
				$editUser->bindParam(3,$lastname);
				$editUser->bindParam(4,$email);
				$editUser->bindParam(5,$phone);
				$editUser->bindParam(6,$role);
				$editUser->bindParam(7,$status);
				$editUser->bindParam(8,$UID);
				$editUser->execute();
				if($role == 10 || $role==200){ //add him/her to layers table
					$status = 1; //active					
					$officer_code =substr($firstname,0,1).substr($lastname,0,1);
				
					$editlawyer = $this->dbCon->prepare("UPDATE lawyer SET firstname=?,middlename=?, lastname=?, status=?,phone=?,email=?,role=? 
					WHERE id=?");
					$editlawyer->bindParam(1,$firstname);
					$editlawyer->bindParam(2,$middlename);
					$editlawyer->bindParam(3,$lastname);
					$editlawyer->bindParam(4,$status);
					$editlawyer->bindParam(5,$phone);
					$editlawyer->bindParam(6,$email);
					$editlawyer->bindParam(7,$role);
					$editlawyer->bindParam(8,$UID);
					$editlawyer->execute();
				}

		  $_SESSION['user-edited']=true;



	} //end adding users



	public function getUsers(){
		//get all users
		try{
			$getUsers = $this->dbCon->prepare("SELECT username, firstname, middlename,lastname, email, phone from users WHERE username != ?" );
			$getUsers->bindParam(1,$_SESSION['user']['username']);
			$getUsers->execute();
			if($getUsers->rowCount()>0){
				$row = $getUsers->fetchAll();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting users
	
		public function getActiveUsers(){
		//get all users
		try{
			$status = 1; //active
			$getUsers = $this->dbCon->prepare("SELECT username, firstname,middlename,lastname, email, phone from users WHERE status=?" );
			$getUsers->bindParam(1,$status);
			$getUsers->execute();
			if($getUsers->rowCount()>0){
				$row = $getUsers->fetchAll();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting active users
	
	//delete user
	public function deleteUser($id){
		$status =0; //inactive
		$deleteUser = $this->dbCon->PREPARE("UPDATE users SET status=? WHERE username=?");
		$deleteUser->bindParam(1,$status);
		$deleteUser->bindParam(2,$id);
		$deleteUser->execute();
				
	}
	
	//get specfic user
	public function getSpecificUser($username){
		
		try{
			$getSpecificUser = $this->dbCon->prepare("SELECT username, password,firstname, middlename,lastname, email, phone, role, roles.name as role_name,
			status, IF(status=1, 'Active','Not Active') as status_name FROM users INNER JOIN roles ON (users.role=roles.id)WHERE username = ?" );
			$getSpecificUser->bindParam(1,$username);
			$getSpecificUser->execute();
			if($getSpecificUser->rowCount()>0){
				$row = $getSpecificUser->fetch();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting users
	

	public function getSingleUser($username){
		//get one users
		try{
			$getUsers = $this->dbCon->prepare("SELECT username, fname, lname, email, phone, users.role_id AS role_id, roles.name AS role from users INNER JOIN roles ON (roles.role_id = users.role_id) WHERE username = ?" );
			$getUsers->bindParam(1, $username);
			$getUsers->execute();
			if($getUsers->rowCount()>0){
				$row = $getUsers->fetch();
				return $row;
			}else{
				return null;
			}
		}catch(PDOException $e){
			echo $e->getMessage();
		}


	} //end of getting single user

	//Update Password
	public function updatePassword($password){

		try{
			$updatepassword = $this->dbCon->prepare("UPDATE users SET password =? WHERE username=?");
			$updatepassword->bindparam(1, $password);	
			$updatepassword->bindparam(2, $_SESSION['user']['username']);			
			$updatepassword->execute();
			
			$_SESSION['password_updated'] =true;
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}


	// user roles 
	public function getRoles(){
		
		try{
			
			$get = $this->dbCon->prepare("SELECT role_id, name FROM roles");
			$get->execute();
			$countRows = $get->rowCount();

			$rows = $get->fetchAll();
			if($countRows>0){
				return $rows;
			}else{
				return null;
			}

		} catch (PDOException $e){
			echo "Lost connection to the database";
		} 

	}

	// add role 
	public function addRole($role){
		try{
			$get = $this->dbCon->prepare("INSERT INTO roles (role_id, name)
				VALUE(:role_id, :name)");
			$get->bindParam(":role_id", $role_id);
			$get->bindParam(":name", $name);
			$get->execute();

			$_SESSION['role-added'] = true;

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	// update role 
	public function updateRole($role_id, $role){
		try{
			$get = $this->dbCon->prepare("UPDATE roles SET name = ? WHERE role_id = ?");
		
			$get->bindParam(1, $name);
			$get->bindParam(2, $role_id);
			$get->execute();

			$_SESSION['role-updated'] = true;
			
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

   
} //End of Class Users


/**
 * 
 */
class Service{
	
	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function addService($image_Path,$service,$description, $category_id){
				$addService = $this->dbCon->prepare("INSERT INTO services (image_url,service, description, category_id) VALUES (:image_url, :service, :description, :category_id)" );

				$addService->execute(array(
						':image_url'=>($image_Path),
						':service'=>($service),
						':description'=>($description),
						':category_id'=>($category_id)));
						  
						  $_SESSION['service-added']=true;
		
	}

	public function getCategoryType(){
		$getCategoryType = $this->dbCon->prepare("SELECT id, category_name FROM category");
		$getCategoryType->execute();

		if($getCategoryType->rowCount()>0){
			$rows = $getCategoryType->fetchAll();

			return  $rows;
		}
		
	}

	public function getServices(){
		$getServices = $this->dbCon->prepare("SELECT id, service as title, description FROM services");
		$getServices->execute();

		if($getServices->rowCount()>0){
			$rows = $getServices->fetchAll();

			return  $rows;
		}
		
	}


public function getServicesPerCategory($id){
		$getServicesPerCategory = $this->dbCon->prepare("SELECT id, service, description FROM services WHERE category_id = ?");
		$getServicesPerCategory->bindParam(1,$id);
		$getServicesPerCategory->execute();

		if($getServicesPerCategory->rowCount()>0){
			$rows = $getServicesPerCategory->fetchAll();

			return  $rows;
		}
		
	}


	public function getSpecificService($id){
		$getSpecificService = $this->dbCon->Prepare("SELECT id, service, description, category_id, image_url FROM services WHERE id=?");
		$getSpecificService->bindParam(1,$id);
		$getSpecificService->execute();
		
		if($getSpecificService->rowCount()>0){
			$row = $getSpecificService->fetch();
			return $row;
		}
	} //end of getting Specific service
	
	


	public function editService($service, $description, $bannerpath, $service_id){
		$editService = $this->dbCon->PREPARE("UPDATE services SET service=?, description=?, image_url=? WHERE id=?");
		$editService->bindParam(1,$service);
		$editService->bindParam(2,$description);
		$editService->bindParam(3,$bannerpath);
		$editService->bindParam(4,$service_id);
		$editService->execute();
		$_SESSION['service-edited'] = true;
	}

		public function deleteServices($id){
		$deleteServices = $this->dbCon->Prepare("DELETE FROM services WHERE id=?");
		$deleteServices->bindParam(1,$id);
		$deleteServices->execute();
		
		
	}
	
	
		public function addPortifolio($image_Path,$portifolio,$description, $category_id){
				$addPortifolio = $this->dbCon->prepare("INSERT INTO services_portifolio (image_url,portifolio, description, category_id) VALUES (:image_url, :portifolio, :description, :category_id)" );

				$addPortifolio->execute(array(
						':image_url'=>($image_Path),
						':portifolio'=>($portifolio),
						':description'=>($description),
						':category_id'=>($category_id)));
						  
						  $_SESSION['portifolio-added']=true;
		
	}


}//End of class Service



Class Contact{

	public function __construct(){

        try{
            $this->dbConct = new Connection();
            $this->dbConct = $this->dbConct->dbConnection();
            $this->dbConct->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Lost connection to the database";
        }
    }//end of constructor
	
	public function sendMail($name, $email, $mailBody){
		//get customer details
		
		$recipient="tiyasomba@gmail.com";
		$subject="Contact From Website";
			
		mail($recipient, $subject, $mailBody, "From: $name <$email>");			
				
		$_SESSION['email_sent']=true;
		
	}






}
//end of Contact Class


class Banner{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function getBanners(){
		
		$getBanners = $this->dbCon->Prepare("SELECT image_url,title FROM banners");
		$getBanners->execute();
		
		if($getBanners->rowCount()>0){
			$row = $getBanners->fetchAll();
			return $row;
		}
	} //end of getting banners
	
		//add Banner
	public function addBanner($image_Path,$title){
				$addBanner = $this->dbCon->prepare("INSERT INTO banners (image_url,title) VALUES (:image_url,:title)" );
				$addBanner->execute(array(
						  ':image_url'=>($image_Path),
						  ':title'=>$title));
						  
						  $_SESSION['banner-added']=true;
		
	}
	
	public function deleteBanner($image_url){
		$deleteBanner =$this->dbCon->PREPARE("DELETE FROM banners WHERE image_url=?");
		$deleteBanner->bindParam(1,$image_url);
		$deleteBanner->execute();
		
		unlink($image_url);
	}

}

//end of Banner Class


class News{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function getNews(){
		$getNews = $this->dbCon->Prepare("SELECT id, title, news, image_url,news.date_added, CONCAT(firstname,' ',middlename,' ',lastname) as author 
		FROM news INNER JOIN users ON (users.username=news.users_username) ORDER BY news.date_added DESC LIMIT 4");
		$getNews->execute();
		
		if($getNews->rowCount()>0){
			$rows = $getNews->fetchAll();
			return $rows;
		}
	} //end of getting news
	
	public function getSpecificNews($id){
		$getSpecificNews = $this->dbCon->Prepare("SELECT id, title, news, image_url,news.date_added, CONCAT(firstname,' ',middlename,' ',lastname) as author FROM news INNER JOIN users ON (users.username=news.users_username) WHERE id=?");
		$getSpecificNews->bindParam(1,$id);
		$getSpecificNews->execute();
		
		if($getSpecificNews->rowCount()>0){
			$row = $getSpecificNews->fetch();
			return $row;
		}
	} //end of getting news
	
	//add news
	public function addNews($image_Path,$title,$content){
				$date = DATE("Y-m-d h:i");
				$addNews = $this->dbCon->prepare("INSERT INTO news (title,news,image_url,date_added,users_username) VALUES (:title,:news,:image_url,:date_added,:users_username)" );
				$addNews->execute(array(

						  ':title'=>($title),
						  ':news'=>($content),
						  ':image_url'=>($image_Path),
						  ':date_added'=>($date),
						  'users_username'=>$_SESSION['user']['username']
						  ));
						  
						  $_SESSION['news-added']=true;
		
	}
	
	
	
	public function editNews($bannerpath,$title,$news,$news_id){
		$editNews = $this->dbCon->PREPARE("UPDATE news SET title=?, news=?, image_url=? WHERE id=?");
		$editNews->bindParam(1,$title);
		$editNews->bindParam(2,$news);
		$editNews->bindParam(3,$bannerpath);
		$editNews->bindParam(4,$news_id);
		$editNews->execute();
		$_SESSION['news-edited'] = true;
	}
	
	public function deleteNews($id){
		$deleteNews = $this->dbCon->Prepare("DELETE FROM news WHERE id=?");
		$deleteNews->bindParam(1,$id);
		$deleteNews->execute();
		
		
	}
}


class Portfolio{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function getPortifolio(){
		$getPortifolio = $this->dbCon->Prepare("SELECT id, portifolio, description,category_id, image_url FROM services_portifolio");
		$getPortifolio->execute();
		
		if($getPortifolio->rowCount()>0){
			$row = $getPortifolio->fetchAll();
			return $row;
		}
	} //end of getting portifolio
	

	
	public function editPortifolio($bannerpath,$title,$content,$id){
		$editPortifolio = $this->dbCon->PREPARE("UPDATE services_portifolio SET portifolio=?, description=?, image_url=? WHERE id=?");
		$editPortifolio->bindParam(1,$title);
		$editPortifolio->bindParam(2,$content);
		$editPortifolio->bindParam(3,$bannerpath);
		$editPortifolio->bindParam(4,$id);
		$editPortifolio->execute();
		$_SESSION['portfolio-edited'] = true;
	}

}

class Category{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function getCategories(){
		$getCategories = $this->dbCon->Prepare("SELECT id, category_name FROM category");
		$getCategories->execute();
		
		if($getCategories->rowCount()>0){
			$row = $getCategories->fetchAll();
			return $row;
		}
	} //end of getting categories
	

	
	public function addCategory($name){
		$addCategory = $this->dbCon->prepare("INSERT INTO category (category_name) VALUES (:category_name)" );
				$addCategory->execute(array(
						  ':category_name'=>($name)));
						  
						  $_SESSION['category-added']=true;
	}

}


class Customer{
	private $dbCon;

//private $username;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

	public function getCustomers(){
		$getCustomers = $this->dbCon->Prepare("SELECT id, name, logo,description FROM customers");
		$getCustomers->execute();
		
		if($getCustomers->rowCount()>0){
			$row = $getCustomers->fetchAll();
			return $row;
		}
	} //end of getting categories
	

	
	public function addCustomer($name,$logo,$description){
		$addCustomer = $this->dbCon->prepare("INSERT INTO customers (name,logo,description) VALUES (:name,:logo,:description)" );
				$addCustomer->execute(array(
						  ':name'=>($name),
						   ':logo'=>($logo),
						   ':description'=>($description)
						   ));
						  
						  $_SESSION['customer-added']=true;
	}

}






?>