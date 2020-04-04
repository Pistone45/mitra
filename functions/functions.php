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
		$login_query = $this->dbCon->prepare("SELECT username, firstname, lastname, phone, email, password FROM users WHERE username=?" );
				$login_query->bindParam(1, $username);
				$login_query->execute();
				
				if($login_query->rowCount() ==1){
				$row = $login_query -> fetch();
				$hash_pass =trim($row['password']);
				//verify password
				if (password_verify($password, $hash_pass)) {
					// Success!
					$_SESSION['user'] = $row;
					
					header("Location: index.php");
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
	
		public function getAllUsers(){
		//get all users
		try{
			$getUsers = $this->dbCon->prepare("SELECT username, firstname,middlename,lastname, email, phone from users " );
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
	public function updatepassword($username, $password){

		try{
			$updatepassword = $this->dbCon->prepare("UPDATE users SET password =? WHERE username=?");
			$updatepassword->bindparam(1, $password);	
			$updatepassword->bindparam(2, $username);			
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

Class Player{
	private $dbCon;

	public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}

			// add a player

	public function addPlayer($path, $team){
		
		try{
			//echo $path; die();
				$handle = @fopen($path, "r"); //read line one by one
				$values='';

				while (!feof($handle)) // Loop til end of file.
				{
					$buffer = fgets($handle, 4096); // Read a line.
					list($a,$b,$c)=explode("|",$buffer);//Separate string by the means of |
					//values.=($a,$b,$c);// save values and use insert query at last or
					//generate player ID
					$player_id = $team.substr($a,0,1).$b.$c;
					//check if player exist
					$checkPlayer = $this->dbCon->prepare("SELECT player_id  FROM players WHERE player_id =?");
					$checkPlayer->bindParam(1, $player_id);			
					$checkPlayer->execute();
					
					if($checkPlayer->rowCount()>0){
						// dont add, player exists 
						$_SESSION['player-exists'] = true;
					}else{
						// add player
						
						$addTeam = $this->dbCon->prepare("INSERT INTO players (player_id,fname,middle_name, lastname,team_id)VALUES(:player_id,:fname,:middle_name, :lastname,:team_id)");
						$addTeam->execute(array(
								  ':player_id'=>($player_id),
								  ':fname'=>($a),
								  ':middle_name'=>($b),
								  ':lastname'=>($c),
								  ':team_id'=>($team)));
								  

						$_SESSION['player-added'] = true;
					}
					
				}
				//unlink($path);
				
				
			
			

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	
		public function addSinglePlayer($player_id,$team,$fname,$mname,$lname){
		
		try{
				//check if player exist
					$checkPlayer = $this->dbCon->prepare("SELECT player_id  FROM players WHERE player_id =?");
					$checkPlayer->bindParam(1, $player_id);			
					$checkPlayer->execute();
					
					if($checkPlayer->rowCount()>0){
						// dont add, player exists 
						$_SESSION['single-player-exists'] = true;
					}else{
						// add player
						
						$addTeam = $this->dbCon->prepare("INSERT INTO players (player_id,fname,middle_name, lastname,team_id)VALUES(:player_id,:fname,:middle_name, :lastname,:team_id)");
						$addTeam->execute(array(
								  ':player_id'=>($player_id),
								  ':fname'=>($fname),
								  ':middle_name'=>($mname),
								  ':lastname'=>($lname),
								  ':team_id'=>($team)));
								  

						$_SESSION['single-player-added'] = true;
					}
					
			
				

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	// get players
	public function getHomePlayers($home){
		try{
			$getPlayers = $this->dbCon->prepare("SELECT player_id, fname, middle_name, lastname, players.team_id AS team_id, teams.name AS name FROM players INNER JOIN teams ON (teams.team_id = players.team_id) WHERE players.team_id=?");
			$getPlayers->bindParam(1,$home);			
			$getPlayers->execute();
			$rows = $getPlayers->fetchAll();

			if($getPlayers->rowCount()>0){
				return $rows;
			}else{
				return null;
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	
		public function getAwayPlayers($away){
		try{
			$getPlayers = $this->dbCon->prepare("SELECT player_id, fname, middle_name, lastname, players.team_id AS team_id, teams.name AS name FROM players INNER JOIN teams ON (teams.team_id = players.team_id) WHERE players.team_id=?");
			$getPlayers->bindParam(1,$away);			
			$getPlayers->execute();
			$rows = $getPlayers->fetchAll();

			if($getPlayers->rowCount()>0){
				return $rows;
			}else{
				return null;
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	// get players
	public function getPlayers($team_id){
		try{
			$getPlayers = $this->dbCon->prepare("SELECT player_id, fname, middle_name, lastname, players.team_id AS team_id, teams.name AS name FROM players INNER JOIN teams ON (teams.team_id = players.team_id) WHERE players.team_id=?");
			$getPlayers->bindParam(1,$team_id);			
			$getPlayers->execute();
			$rows = $getPlayers->fetchAll();

			if($getPlayers->rowCount()>0){
				return $rows;
			}else{
				return null;
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	
		// get single player
	public function getSinglePlayer($player_id){
		//echo $player_id; die();
		try{
			$getPlayers = $this->dbCon->prepare("SELECT player_id, fname, middle_name, lastname, players.team_id AS team_id, teams.name AS name FROM players INNER JOIN teams ON (teams.team_id = players.team_id) WHERE players.player_id=?");
			$getPlayers->bindParam(1,$player_id);			
			$getPlayers->execute();
			

			if($getPlayers->rowCount()>0){
				$rows = $getPlayers->fetch();
				return $rows;
			}else{
				return null;
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	
	
	// transfer player
	public function transferPlayer($old_team,$new_team,$player_id){
		$date = DATE('Y-m-d H:i:s');		
		//insert into transfer table
		$transferPlayer = $this->dbCon->PREPARE("INSERT INTO transfers (player_id,old_team,new_team,transfer_date) VALUES (:player_id,:old_team,:new_team,:transfer_date)");
		$transferPlayer->execute(array(
		':player_id'=>$player_id,
		':old_team'=>$old_team,
		':new_team'=>$new_team,
		':transfer_date'=>$date
		));
		
		//update team id in players table
		$updatePlayerTeam = $this->dbCon->PREPARE("UPDATE players SET team_id=? WHERE player_id=?");
		$updatePlayerTeam->bindParam(1, $new_team);
		$updatePlayerTeam->bindParam(2, $player_id);
		$updatePlayerTeam->execute();
		
		$_SESSION['player_transfered'] = true;
	}
	// get single player
	public function getTopScorers($limit,$season){
		try{
			$getTopScorers = $this->dbCon->prepare("SELECT goals.player_id,fname, middle_name, lastname, game_id, sum(score) as score FROM goals
			INNER JOIN players ON (players.player_id=goals.player_id) WHERE season=? GROUP BY goals.player_id ORDER BY score DESC LIMIT $limit");
			$getTopScorers->bindParam(1,$season);
			$getTopScorers->execute();
			$rows = $getTopScorers->fetchAll();

			if($getTopScorers->rowCount()>0){
				return $rows;
			}else{
				return null;
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	
		public function getTotalScores($season){
		try{
			$getTotalScores = $this->dbCon->prepare("SELECT sum(score) as total FROM goals WHERE season=?");
			$getTotalScores->bindparam(1,$season);
			$getTotalScores->execute();
			$rows = $getTotalScores->fetch();

			if($getTotalScores->rowCount()>0){
				return $rows;
			}else{
				return null;
			}

		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}

}



Class Abstracts{
	private $dbCon;
	//private $username;

	public function __construct(){
		try{
			$this->dbCon = new Connection();

			$this->dbCon = $this->dbCon->dbConnection();
			$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}


	// add a team

	public function submitAbstract($name,$email,$authors,$abstract,$abstract_title){
		
		try{
			// check if team exists 
			$check = $this->dbCon->prepare("SELECT email FROM abstracts WHERE email =?");
			$check->bindParam(1, $email);			
			$check->execute();
			
			if($check->rowCount()>0){
				// dont add, team exists 
				$_SESSION['abstract-exists'] = true;
			}else{				
				$submitAbstract = $this->dbCon->prepare("INSERT INTO abstracts (name,email, author,abstract,abstract_title)VALUES(:name,:email, :author,:abstract,:abstract_title)");
				$submitAbstract->execute(array(
						  ':name'=>($name),
						  ':email'=>($email),
						  ':author'=>($authors),
						  ':abstract'=>($abstract),
						  ':abstract_title'=>($abstract_title)
						  ));
				
				$_SESSION['abstract-sent'] = true;
			}

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}


	

	// get all teams 
	public function getAbstracts(){
		
		try{			
			$getAbstracts = $this->dbCon->prepare("SELECT id,name,email, author,abstract,abstract_title FROM abstracts");
			$getAbstracts->execute();
			
			
			if($getAbstracts->rowCount()>0){
				$rows = $getAbstracts->fetchAll();
				return $rows;
			}else{
				return null;
			}

		} catch (PDOException $e){
			echo $e->getMessage();
		} 

	}


		// get all teams per zone 
	public function getTeamsPerZone($zone){
		
		try{			
			$getTeamsPerZone = $this->dbCon->prepare("SELECT id, name, zones_id, logo FROM teams WHERE zones_id =?");
			$getTeamsPerZone->bindParam(1,$zone);
			$getTeamsPerZone->execute();
			
			
			if($getTeamsPerZone->rowCount()>0){
				$rows = $getTeamsPerZone->fetchAll();
				return $rows;
			}else{
				return null;
			}

		} catch (PDOException $e){
			echo $e->getMessage();
		} 

	}
	
	public function getSingleTeam($team_id){
		
		try{
			
			$get = $this->dbCon->prepare("SELECT team_id, name FROM teams WHERE team_id = ?");
			$get->bindParam(1, $team_id);
			$get->execute();
			$countRows = $get->rowCount();

			$rows = $get->fetch();
			if($countRows>0){
				return $rows;
			}else{
				return null;
			}

		}catch (PDOException $e){
			echo $e->getMessage();
		} 

	}


}

// class for venues 
Class Venue{
	private $dbCon;
	//private $username;

	public function __construct(){
		try{
			$this->dbCon = new Connection();

			$this->dbCon = $this->dbCon->dbConnection();
			$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}


	// add a venue

	public function addVenue($name){
		try{
			// check if team exists 
			$checkVenue = $this->dbCon->prepare("SELECT id FROM venue WHERE name = ?");
			$checkVenue->bindParam(1, $name);
			$checkVenue->execute();

			if($checkVenue->rowCount()>0){
				// dont add, team exists 
				$_SESSION['ground-exists'] = true;
			}else{
				// add venue
				$addVenue = $this->dbCon->prepare("INSERT INTO venue (name) VALUES(:name)");
				$addVenue->execute(array(
						  ':name'=>($name)));

				$_SESSION['venue-added'] = true;
			}

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}

	// update team
	public function updateVenue($name, $venue_id){
		try{
			$update = $this->dbCon->prepare("UPDATE venues SET name = ? WHERE venue_id =?");
			$update->bindParam(1, $name);
			$update->bindParam(2, $venue_id);
			$update->execute();

			$_SESSION['venue-updated'] = true;

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}

	// delete venue
	public function deleteVenue($venue_id){
		try{
			$update = $this->dbCon->prepare("DELETE FROM venues WHERE venue_id =?");
			$update->bindParam(1, $name);
			$update->bindParam(2, $venue_id);
			$update->execute();

			$_SESSION['team-updated'] = true;

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}


	// get all venues 
	public function getVenueList(){
		
		try{
			
			$getVenue = $this->dbCon->prepare("SELECT id, name FROM venue");
			$getVenue->execute();
			
			$rows = $getVenue->fetchAll();
			if($getVenue->rowCount()>0){
				return $rows;
			}else{
				return null;
			}

		} catch (PDOException $e){
			echo $e->getMessage();
		} 

	}


	public function getSingleVenue($venue_id){
		
		try{
			
			$get = $this->dbCon->prepare("SELECT venue_id, name FROM venues WHERE venue_id = ?");
			$get->bindParam(1, $venue_id);
			$get->execute();
			$countRows = $get->rowCount();

			$rows = $get->fetch();
			if($countRows>0){
				return $rows;
			}else{
				return null;
			}

		} catch (PDOException $e){
			echo $e->getMessage();
		} 

	}


}



Class Game{
	public function __construct(){

        try{
            $this->dbConct = new Connection();
            $this->dbConct = $this->dbConct->dbConnection();
            $this->dbConct->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Lost connection to the database";
        }
    }//end of constructor


	
	// add Fixture

	public function addFixture($awayTeam,$homeTeam,$venue,$date_time){
		//echo $venue; die();
		try{
				// add fixture
				$season ="2019";
				$addFixture = $this->dbConct->prepare("INSERT INTO fixture (home_team, away_team,venue_id, date_time,season) VALUES(:home_team, :away_team,:venue_id, :date_time,:season)");
				$addFixture->execute(array(
						  ':home_team'=>($homeTeam),
						  ':away_team'=>($awayTeam),
						  ':venue_id'=>($venue),
						  ':date_time'=>($date_time),
						  ':season'=>($season),
						  ));
				$id = $this->dbConct->lastInsertId();
				
				//add into results table
				$addResult = $this->dbConct->prepare("INSERT INTO results (fixture_id,home_team, away_team,season) VALUES(:fixture_id,:home_team, :away_team,:season)");
				$addResult->execute(array(
						  ':fixture_id'=>($id),
						  ':home_team'=>($homeTeam),
						  ':away_team'=>($awayTeam),
						  ':season'=>($season)
						  ));
						  
				$_SESSION['fixture-added'] = true;
			

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	
	public function addResults($homeTeam,$awayTeam,$fixture_id,$homePlayer_id,$awayPlayer_id,$season){
		//echo $venue; die();
		try{
		
				// add Results
				$status = 1; //played status
				$datetime = DATE('Y-m-d H:i:s');
				$addResults = $this->dbConct->prepare("UPDATE results SET home_score=?, away_score=?, status=?, last_updated=?,updated_by=?,season=? WHERE fixture_id=?");
				$addResults->bindParam(1, $homeTeam);
				$addResults->bindParam(2,$awayTeam);
				$addResults->bindParam(3,$status);
				$addResults->bindParam(4,$datetime);
				$addResults->bindParam(5,$_SESSION['user']['username']);
				$addResults->bindParam(6,$season);
				$addResults->bindParam(7,$fixture_id);
				
				$addResults->execute();
				
				//add Home scorer
				$score = 1;
				if($homePlayer_id !="" && $awayPlayer_id !=""){ //both team scored
				
					$addHomeScorer = $this->dbConct->prepare("INSERT into goals (player_id, game_id, score,last_updated,season) VALUES(:player_id,:game_id,:score,:last_updated,:season)");
					$addHomeScorer->execute(array(
							  ':player_id'=>($homePlayer_id),
							  ':game_id'=>($fixture_id),
							  ':score'=>($score),
							  ':last_updated'=>($datetime),
							  ':season'=>($season)
							  ));
							  
					$addAwayScorer = $this->dbConct->prepare("INSERT into goals (player_id, game_id, score,last_updated,season) VALUES(:player_id,:game_id,:score,:last_updated,:season)");
					$addAwayScorer->execute(array(
							  ':player_id'=>($awayPlayer_id),
							  ':game_id'=>($fixture_id),
							  ':score'=>($score),
							  ':last_updated'=>($datetime),
							   ':season'=>($season)
							  ));
					
					$_SESSION['results-added'] = true;
				
				}elseif($homePlayer_id !="" && $awayPlayer_id ==""){ //only home team scored
				
					$addHomeScorer = $this->dbConct->prepare("INSERT into goals (player_id, game_id, score,last_updated,season) VALUES(:player_id,:game_id,:score,:last_updated,:season)");
					$addHomeScorer->execute(array(
							  ':player_id'=>($homePlayer_id),
							  ':game_id'=>($fixture_id),
							  ':score'=>($score),
							  ':last_updated'=>($datetime),
							   ':season'=>($season)
							  ));
						
					$_SESSION['results-added'] = true;
					
				}elseif($homePlayer_id =="" && $awayPlayer_id !=""){ //only away team scored
								  
					$addAwayScorer = $this->dbConct->prepare("INSERT into goals (player_id, game_id, score,last_updated,season) VALUES(:player_id,:game_id,:score,:last_updated,:season)");
					$addAwayScorer->execute(array(
							  ':player_id'=>($awayPlayer_id),
							  ':game_id'=>($fixture_id),
							  ':score'=>($score),
							  ':last_updated'=>($datetime),
							   ':season'=>($season)							  
							  ));
					
					$_SESSION['results-added'] = true;
					
				}
				
			$_SESSION['results-added'] = true;

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	
	
	public function addZeroResults($homeScore,$awayScore,$fixture_id,$season){
		//echo $venue; die();
		try{
		
				// add Results
				//$status = 2; //Final status
				$datetime = DATE('Y-m-d H:i:s');
				$addResults = $this->dbConct->prepare("UPDATE results SET home_score=?, away_score=?, last_updated=?,updated_by=?,season=? WHERE fixture_id=?");
				$addResults->bindParam(1, $homeScore);
				$addResults->bindParam(2,$awayScore);
				$addResults->bindParam(3,$datetime);
				$addResults->bindParam(4,$_SESSION['user']['username']);
				$addResults->bindParam(5,$fixture_id);
				$addResults->bindParam(6,$season);
				$addResults->execute();
				
		

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	
	public function updateGameStatus($status,$fixture_id){
		//echo $venue; die();
		try{
			
				// add fixture
				//$status = 2; //played status
				$addResults = $this->dbConct->prepare("UPDATE fixtures SET status=? WHERE fixture_id=?");
				$addResults->bindParam(1, $status);				
				$addResults->bindParam(2,$fixture_id);
				$addResults->execute();
				
				$updateStatus = $this->dbConct->prepare("UPDATE results SET status=? WHERE fixture_id=?");
				$updateStatus->bindParam(1, $status);				
				$updateStatus->bindParam(2,$fixture_id);
				$updateStatus->execute();
			
		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	
	public function endGame($fixture_id,$homeScore,$awayScore){
		
		try{
			//get teams
			$getTeams =$this->dbConct->PREPARE("SELECT home_team, away_team, fixture_id FROM fixture WHERE fixture_id=?");
			$getTeams->bindParam(1,$fixture_id);
			$getTeams->execute();
			if($getTeams->rowCount()>0){ //data retrieved
				$row = $getTeams->fetch();
				$home_team = $row['home_team'];
				$away_team = $row['away_team'];
			}
			
			//check if the fixture is not already updated
			$status =2;
			$selectFixture = $this->dbConct->prepare("SELECT fixture_id, status FROM results WHERE fixture_id=? AND status=?");
			$selectFixture->bindParam(1,$fixture_id);
			$selectFixture->bindParam(2,$status);
			$selectFixture->execute();
			$row = $selectFixture->fetch();
			if($selectFixture->rowCount()>0){ //already updated
			
				$_SESSION['already-updated']= true;
			}else{//update Logo Table
					// update home team results
				if($homeScore>$awayScore){
					
					//begin transaction
					$this->dbConct->beginTransaction();
					$homePoints = 2;
					$awayPoints =1;
					
					//select points, scores and conceded goals for home team
					$getDetails = $this->dbConct->prepare("SELECT teams_id,points, scored, conceded,played,won,lost,forfeit FROM log_table WHERE teams_id=?");
					$getDetails->bindParam(1,$home_team);
					$getDetails->execute();
					
					
					if($getDetails->rowCount()>0){ //echo $home_team; die();
						$row = $getDetails->fetch();
						$points=  $row['points'] + $homePoints;
						$scored = $row['scored']+ $homeScore;
						$conceded = $row['conceded']+ $awayScore;
						$played = $row['played']+ 1;
						$won = $row['won'] +1;
						
						//update home team log table
						$addResult = $this->dbConct->prepare("UPDATE log_table SET points=?, scored=?, conceded=?,played=?, won=? WHERE teams_id=?");
						$addResult->bindParam(1, $points);
						$addResult->bindParam(2, $scored);	
						$addResult->bindParam(3, $conceded);
						$addResult->bindParam(4, $played);
						$addResult->bindParam(5, $won);	
						$addResult->bindParam(6, $home_team);
						$addResult->execute();
						
						
					}
					
					//select points, scores and conceded goals for away team
					$getAwayTeamDetails = $this->dbConct->prepare("SELECT teams_id,points, scored, conceded,played,won,lost,forfeit FROM log_table WHERE teams_id=?");
					$getAwayTeamDetails->bindParam(1,$away_team);
					$getAwayTeamDetails->execute();
					
					$row = $getAwayTeamDetails->fetch();
					if($getAwayTeamDetails->rowCount()>0){
						$points=  $row['points'] + $awayPoints;
						$scored = $row['scored']+ $awayScore;
						$conceded = $row['conceded']+ $homeScore;
						$played = $row['played']+ 1;
						$lost= $row['lost'] +1;
						
						//update home team log table
						$addResult = $this->dbConct->prepare("UPDATE log_table SET points=?, scored=?, conceded=?,played=?, lost=? WHERE teams_id=?");
						$addResult->bindParam(1, $points);
						$addResult->bindParam(2, $scored);	
						$addResult->bindParam(3, $conceded);							
						$addResult->bindParam(4, $played);
						$addResult->bindParam(5, $lost);
						$addResult->bindParam(6, $away_team);
						$addResult->execute();
					}
					
					//commit transaction
				$status = 2; //played status
				$addResults = $this->dbConct->prepare("UPDATE fixture SET status=? WHERE fixture_id=?");
				$addResults->bindParam(1, $status);				
				$addResults->bindParam(2,$fixture_id);
				$addResults->execute();
				
				$updateStatus = $this->dbConct->prepare("UPDATE results SET status=? WHERE fixture_id=?");
				$updateStatus->bindParam(1, $status);				
				$updateStatus->bindParam(2,$fixture_id);
				$updateStatus->execute();
				
					$this->dbConct->commit();
					
					$_SESSION['log_updated'] = true;
					
					
					
				}elseif($homeScore<$awayScore){
					//echo 'fffwt'; die();
					//begin transaction
					$this->dbConct->beginTransaction();
					$homePoints = 1;
					$awayPoints =2;
					
					//select points, scores and conceded goals for home team
					$getDetails = $this->dbConct->prepare("SELECT teams_id,points, scored, conceded,played,won,lost,forfeit FROM log_table WHERE teams_id=?");
					$getDetails->bindParam(1,$home_team);
					$getDetails->execute();
					
					$row = $getDetails->fetch();
					if($getDetails->rowCount()>0){
						$points=  $row['points'] + $homePoints;
						$scored = $row['scored']+ $homeScore;
						$conceded = $row['conceded']+ $awayScore;
						$played = $row['played']+ 1;
						$lost = $row['lost'] +1;
						
						//update home team log table
						$addResult = $this->dbConct->prepare("UPDATE log_table SET points=?, scored=?, conceded=?,played=?,lost=? WHERE teams_id=?");
						$addResult->bindParam(1, $points);
						$addResult->bindParam(2, $scored);	
						$addResult->bindParam(3, $conceded);
						$addResult->bindParam(4, $played);
						$addResult->bindParam(5, $lost);
						$addResult->bindParam(6, $home_team);						
						$addResult->execute();
					}
					
					//select points, scores and conceded goals for away team
					$getAwayTeamDetails = $this->dbConct->prepare("SELECT teams_id, points, scored, conceded,played,won,lost,forfeit FROM log_table WHERE teams_id=?");
					$getAwayTeamDetails->bindParam(1,$away_team);
					$getAwayTeamDetails->execute();
					
					$row = $getAwayTeamDetails->fetch();
					if($getAwayTeamDetails->rowCount()>0){
						$points=  $row['points'] + $awayPoints;
						$scored = $row['scored']+ $awayScore;
						$conceded = $row['conceded']+ $homeScore;
						$played = $row['played']+ 1;
						$won = $row['won'] = +1;
						
						//update home team log table
						$addResult = $this->dbConct->prepare("UPDATE log_table SET points=?, scored=?, conceded=?,played=?, won=? WHERE teams_id=?");
						$addResult->bindParam(1, $points);
						$addResult->bindParam(2, $scored);	
						$addResult->bindParam(3, $conceded);							
						$addResult->bindParam(4, $played);
						$addResult->bindParam(5, $won);
						$addResult->bindParam(6, $away_team);
						$addResult->execute();
					}
					
					//commit transaction
					
				$status = 2; //played status
				$addResults = $this->dbConct->prepare("UPDATE fixture SET status=? WHERE fixture_id=?");
				$addResults->bindParam(1, $status);				
				$addResults->bindParam(2,$fixture_id);
				$addResults->execute();
				
				$updateStatus = $this->dbConct->prepare("UPDATE results SET status=? WHERE fixture_id=?");
				$updateStatus->bindParam(1, $status);				
				$updateStatus->bindParam(2,$fixture_id);
				$updateStatus->execute();
				
					$this->dbConct->commit();
					$_SESSION['log_updated'] = true;
					
				}
				
				//end of Log Table update
			}
			
			//end of checking
				
				
			

		}catch (PDOException $e){
			echo $e->getMessage();
			//Rollback the transaction.
			$this->dbCon->rollBack();
		}
	}
	

    // get all fixtures 
    public function getFixture($status){
    	try{
			
    		$getFixture = $this->dbConct->prepare("SELECT fixture_id, date_time, venue.name as venue, 
			t.name as home_team, t.logo as home_logo,t2.logo as away_logo, t2.name as away_team FROM fixture INNER JOIN
			teams as t ON (t.id=fixture.home_team) INNER JOIN teams as t2
			ON(t2.id=fixture.away_team) INNER JOIN venue ON
			(venue.id=fixture.venue_id) WHERE DATE(date_time) >=CURDATE() AND status !=? 
			ORDER BY date_time ASC");
			$getFixture->bindParam(1,$status);
    		$getFixture->execute();
    		$rows = $getFixture->fetchAll();
    		
    		if($getFixture->rowCount()> 0){
    			return $rows;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
		public function getSpecificFixture($id){
		$date = DATE("Y-m-d"); //current date
		$getFixture =$this->dbConct->PREPARE("SELECT fixture_id, date_time,homeTeam.id as home,homeTeam.name as home_team,awayTeam.id as away,awayTeam.name as away_team,venue.name as venue  FROM fixture INNER JOIN teams as homeTeam ON (homeTeam.id =fixture.home_team) INNER JOIN teams as awayTeam ON (awayTeam.id=fixture.away_team) INNER JOIN venue ON (venue.id=fixture.venue_id) WHERE fixture_id =? ");
		$getFixture->bindParam(1,$id);
		$getFixture->execute();
		
		if($getFixture->rowCount()>0){
			$row = $getFixture->fetch();			
			return $row;
		}
	} //end of getting specific fixture
	
		
	public function getGameResults($id){
		$getGameResults =$this->dbConct->PREPARE("SELECT results_id,fixture_id, IF(status=1,'In Progres','No Started or No Scores') as status, home_team_score,away_team_score,updated_by,last_updated FROM results WHERE fixture_id =? ");
		$getGameResults->bindParam(1,$id);
		$getGameResults->execute();
		
		if($getGameResults->rowCount()>0){
			$row = $getGameResults->fetch();			
			return $row;
		}
	} //end of getting specific results
	
		// add Score
		public function addScore($homeScore,$awayScore,$fixture_id){
		try{
				$date = DATE("Y-m-d"); //current date
				$status =1; // in progress status
				$addScore = $this->dbConct->prepare("UPDATE results SET home_team_score=?, away_team_score=?,last_updated=?,updated_by=?,status=? WHERE fixture_id=?");
				$addScore->bindParam(1,$homeScore);
				$addScore->bindParam(2,$awayScore);
				$addScore->bindParam(3,$date);
				$addScore->bindParam(4,$_SESSION['user']['username']);
				$addScore->bindParam(5,$status);
				$addScore->bindParam(6,$fixture_id);
				$addScore->execute();
				
				$_SESSION['score-added'] = true;
			

		}catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	//update fixture
	  public function editFixture($fixture_id, $date,$venue){
    	try{
    		$editFixture = $this->dbConct->prepare("UPDATE fixtures SET date_time=?, venue_id=? WHERE fixture_id=?");
			$editFixture->bindParam(1,$date);
			$editFixture->bindParam(2,$venue);
			$editFixture->bindParam(3,$fixture_id);
    		$editFixture->execute();
			
			$_SESSION['fixture-updated']=true;
    	

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	
		//update fixture
	  public function cancelFixture($fixture_id, $status){
    	try{
    		$cancelFixture = $this->dbConct->prepare("UPDATE fixtures SET status=? WHERE fixture_id=?");
			$cancelFixture->bindParam(1,$status);
			$cancelFixture->bindParam(2,$fixture_id);
    		$cancelFixture->execute();
			
			$upadteResultsStatus = $this->dbConct->prepare("UPDATE results SET status=? WHERE fixture_id=?");
			$upadteResultsStatus->bindParam(1,$status);
			$upadteResultsStatus->bindParam(2,$fixture_id);
    		$upadteResultsStatus->execute();
			
			$_SESSION['fixture-cancelled']=true;
    	

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	
	  public function getScores($season,$status){
    	try{
    		$getScores = $this->dbConct->prepare("SELECT results_id,IF(results.status=1,'In Progres','Game Ended') as game_status, results.fixture_id, t.name as home_team,t.logo as home_logo,t2.logo as away_logo, t2.name as away_team, home_team_score, away_team_score,last_updated FROM results 
			INNER JOIN teams as t ON (t.id=results.home_team) INNER JOIN teams as t2 ON(t2.id=results.away_team)
			INNER JOIN fixture ON (results.fixture_id=fixture.fixture_id) WHERE results.season =? AND results.status !=? ORDER BY last_updated DESC");
			$getScores->bindParam(1,$season);
			$getScores->bindParam(2,$status);
    		$getScores->execute();
    		$rows = $getScores->fetchAll();
    		
    		if($getScores->rowCount()> 0){
    			return $rows;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	    // get todays' fixtures 
    public function getTodaysFixture($status,$played){
    	try{
    		$getFixture = $this->dbConct->prepare("SELECT fixture_id, date_time, venue.name as venue, 
			t.name as home_team, t2.name as away_team FROM fixture INNER JOIN teams as t
			ON (t.id=fixture.home_team) INNER JOIN teams as t2 ON(t2.id=fixture.away_team) 
			INNER JOIN venue ON (venue.id=fixture.venue_id) 
			WHERE status!=? AND status !=? AND DATE(date_time)=CURDATE() ORDER BY date_time ASC");
			$getFixture->bindParam(1,$status);
			$getFixture->bindParam(2,$played);
    		$getFixture->execute();
    		$rows = $getFixture->fetchAll();
    		
    		if($getFixture->rowCount()> 0){
    			return $rows;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	
	    // get todays' fixtures 
    public function getTodaysResults($season,$status){
    	try{
			
    		$getScores = $this->dbConct->prepare("SELECT results_id,IF(results.status=1,'In Progres','Game Ended') as game_status, results.fixture_id, t.name as home_team,t.logo as home_logo,t2.logo as away_logo, t2.name as away_team, home_team_score, away_team_score,last_updated FROM results 
			INNER JOIN teams as t ON (t.id=results.home_team) INNER JOIN teams as t2 ON(t2.id=results.away_team)
			INNER JOIN fixture ON (results.fixture_id=fixture.fixture_id) WHERE results.season =? AND results.status =? ");
			$getScores->bindParam(1,$season);
			$getScores->bindParam(2,$status);
    		$getScores->execute();
    		$rows = $getScores->fetchAll();
    		
    		if($getScores->rowCount()> 0){
    			return $rows;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	
	    // get todays' fixtures 
    public function getGameDetails($fixture_id){
    	try{
    		$getGameDetails = $this->dbConct->prepare("SELECT fixtures.venue_id,venue.name as venue_name ,fixtures.date_time, results.fixture_id, results.home_team, results.away_team, t.name as home_team_name, t2.name as away_team_name, home_score,away_score FROM results INNER JOIN teams as t ON (t.team_id=results.home_team) INNER JOIN teams as t2 ON(t2.team_id=results.away_team) INNER JOIN fixtures ON(fixtures.fixture_id=results.fixture_id) INNER JOIN venue ON(venue
			.venue_id=fixtures.venue_id) WHERE results.fixture_id=?");
			$getGameDetails->bindParam(1,$fixture_id);
    		$getGameDetails->execute();
    		$row = $getGameDetails->fetch();
    		
    		if($getGameDetails->rowCount()> 0){
    			return $row;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	
	  // get all Results 
	  
    public function getResults(){
    	try{
			
    		$getFixture = $this->dbConct->prepare("SELECT fixture_id, date_time, venue.name as venue, t.name as home_team, t2.name as away_team FROM fixtures INNER JOIN teams as t ON (t.team_id=fixtures.home_team) INNER JOIN teams as t2 ON(t2.team_id=fixtures.away_team) INNER JOIN venue ON (venue.venue_id=fixtures.venue_id), last_updated WHERE date_time >=CURDATE() ORDER BY date_time ASC");
			
    		$getFixture->execute();
    		$rows = $getFixture->fetchAll();
    		
    		if($getFixture->rowCount()> 0){
    			return $rows;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	
	 public function getTeams($fixture_id){
    	try{
    		$getTeams = $this->dbConct->prepare("SELECT home_team, away_team FROM fixtures WHERE fixture_id=?");
			$getTeams->bindParam(1,$fixture_id);
    		$getTeams->execute();
    		$row = $getTeams->fetch();
    		
    		if($getTeams->rowCount()> 0){
    			return $row;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }
	
	  public function getLogTable($id,$gender){
    	try{
			
    		$getLogTable = $this->dbConct->prepare("SELECT teams.name as team_id,points, scored,
			conceded,scored-conceded as gd,  played, won, lost, forfeit FROM log_table
			INNER JOIN teams ON (teams.id=log_table.teams_id) WHERE log_table.zones_id =? AND gender =?
			ORDER BY points DESC, gd DESC, scored DESC");
			$getLogTable->bindParam(1,$id);
			$getLogTable->bindParam(2,$gender);
    		$getLogTable->execute();
    		$rows = $getLogTable->fetchAll();
    		
    		if($getLogTable->rowCount()> 0){
    			return $rows;
    		}else{
    			return null;
    		}

    	}catch(PDOException $e){
    		echo $e->getMessage();
    	}
    }


}


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
class Gallery{
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

		//add Album
	public function addAlbum($title,$album_cover){
				
				$addAlbum = $this->dbCon->prepare("INSERT INTO album (name,album_cover) VALUES (:name,:album_cover)" );
				$addAlbum->execute(array(
						  ':name'=>($title),
						  ':album_cover'=>($album_cover)
						  ));
						  
						  $_SESSION['album-added']=true;
		
	}

		
	public function getAlbums(){
		$getAlbums = $this->dbCon->Prepare("SELECT id,name, album_cover FROM album");
		$getAlbums->execute();
		
		if($getAlbums->rowCount()>0){
			$row = $getAlbums->fetchAll();
			return $row;
		}
	} //end of getting albums
	
	//add Photo
	public function addPhoto($album,$image,$caption){
				$date_posted =DATE("Y-m-d H:i");
				$addPhoto = $this->dbCon->prepare("INSERT INTO album_images (image_url,caption,album_id,date_posted) VALUES (:image_url,:caption,:album_id,:date_posted)" );
				$addPhoto->execute(array(
						  ':image_url'=>($image),
						  ':caption'=>($caption),
						  ':album_id'=>($album),
						  ':date_posted'=>($date_posted)
						  ));
						  
						  $_SESSION['photo-added']=true;
		
	}
	
		public function getPhotosPerAlbums($id){
		$getPhotosPerAlbums = $this->dbCon->Prepare("SELECT id,image_url, caption, album_id, date_posted FROM album_images WHERE album_id=?");
		$getPhotosPerAlbums->bindParam(1,$id);
		$getPhotosPerAlbums->execute();
		
		if($getPhotosPerAlbums->rowCount()>0){
			$rows = $getPhotosPerAlbums->fetchAll();
			return $rows;
		}
	} //end of getting sub categories
	public function getSubcategoryPerCategory($id){
		$getSubcategoryPerCategory = $this->dbCon->Prepare("SELECT id, name, category_category_id FROM sub_category WHERE category_category_id=?");
		$getSubcategoryPerCategory->bindParam(1,$id);
		$getSubcategoryPerCategory->execute();
		
		if($getSubcategoryPerCategory->rowCount()>0){
			$row = $getSubcategoryPerCategory->fetchAll();
			return $row;
		}
	} //end of getting sub categories per category ID

	public function deleteAlbum($id){
		//delet all photos from that album 1st
		$deletePhotosPerAlbum = $this->dbCon->PREPARE("DELETE FROM album_images WHERE album_id=?");
		$deletePhotosPerAlbum->bindParam(1,$id);
		$deletePhotosPerAlbum->execute();
		
		//delete the actual album
		$deleteAlbum = $this->dbCon->PREPARE("DELETE FROM album WHERE id=?");
		$deleteAlbum->bindParam(1,$id);
		$deleteAlbum->execute();
		
		
	}
	
		public function getSpecificAlbum($id){
		$getSpecificAlbum = $this->dbCon->Prepare("SELECT id,name, album_cover FROM album WHERE id=?");
		$getSpecificAlbum->bindParam(1,$id);
		$getSpecificAlbum->execute();
		
		if($getSpecificAlbum->rowCount()>0){
			$row = $getSpecificAlbum->fetch();
			return $row;
		}
	} //end of getting a specific album
	
		public function editAlbum($bannerpath,$title,$album_id){
		$editAlbum = $this->dbCon->PREPARE("UPDATE album SET name=?, album_cover=? WHERE id=?");
		$editAlbum->bindParam(1,$title);
		$editAlbum->bindParam(2,$bannerpath);
		$editAlbum->bindParam(3,$album_id);
		$editAlbum->execute();
		
		$_SESSION['album-edited'] =true;
	}
	
	
}

//end of gallery class
Class Category{

	public function __construct(){

        try{
            $this->dbConct = new Connection();
            $this->dbConct = $this->dbConct->dbConnection();
            $this->dbConct->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            echo "Lost connection to the database";
        }
    }//end of constructor
	
	public function addZone($path, $title){
		
		//check if banner already exist
		$checkBanner = $this->dbConct->prepare("SELECT image_path FROM banner where image_path=?");	
		$checkBanner->bindparam(1, $path);
		$checkBanner->execute();
		
		if ($checkBanner->rowCount()>0) { //Events found
			
			$row = $checkBanner->fetch();
			$_SESSION['banner-found']=true;
		
			}else{//banner not found, add banner
				
				$addBanner = $this->dbConct->prepare("INSERT INTO banner (image_path, title) VALUES (:image_path, :title)" );
				$addBanner->execute(array(
						  ':image_path'=>($path),
						  ':title'=>($title)));
							
						
				$_SESSION['banner-added']=true;
			}
		
		
		
	}
	
	
		public function getCategories(){
			try{
				$getCategories = $this->dbConct->prepare("SELECT id, name,level FROM category");	
				$getCategories->execute();
					
				if ($getCategories->rowCount()>0) { 
					
					$row = $getCategories->fetchAll();
					return $row;
				}
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			
			
		}
		
		public function getSpecificZone($id){
			try{
				$getSpecificZone = $this->dbConct->prepare("SELECT id, name FROM zones WHERE id=?");
				$getSpecificZone->bindParam(1,$id);
				$getSpecificZone->execute();
					
				if ($getSpecificZone->rowCount()>0) { 
					
					$row = $getSpecificZone->fetch();
					return $row;
				}
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			
			
		}
	
		public function deleteBanner($id){
		//delete banner details
		
		$deleteBanner = $this->dbConct->prepare("DELETE FROM banner WHERE image_path=?");
		$deleteBanner->bindparam(1, $id);
		$deleteBanner->execute();
			unlink($id);
			$_SESSION['banner-deleted'] = true;
		
	}





}
// end of class zone

class Partner{
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

	public function getPartners(){
		
		$getPartners = $this->dbCon->Prepare("SELECT image_url FROM sponsors");
		$getPartners->execute();
		
		if($getPartners->rowCount()>0){
			$row = $getPartners->fetchAll();
			return $row;
		}
	} //end of getting Partners
	
		//add Partner
	public function addPartner($image_Path,$name){
				$addPartner = $this->dbCon->prepare("INSERT INTO sponsors (image_url,name) VALUES (:image_url,:name)" );
				$addPartner->execute(array(
						  ':image_url'=>($image_Path),
						   ':name'=>($name)
						  ));
						  
						  $_SESSION['partner-added']=true;
		
	}
	
	public function deletePartner($image_url){
		$deletePartner =$this->dbCon->PREPARE("DELETE FROM sponsors WHERE image_url=?");
		$deletePartner->bindParam(1,$image_url);
		$deletePartner->execute();
		
		unlink($image_url);
	}

}


/**
 * 
 */
class Booking{
	
	private $dbCon;
	
		public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}


	public function AddBooking($check_in, $check_out, $room_type_id, $aldut_no, $children, $name, $email, $phone){

		  
				$addBooking = $this->dbCon->prepare("INSERT INTO bookings (check_in, check_out, room_type_id, aldut_no, children, name, email, phone) VALUES (:check_in, :check_out, :room_type_id, :aldut_no, :children, :name, :email, :phone)" );
				$addBooking->execute(array(

						  ':check_in'=>($check_in),
						  ':check_out'=>($check_out),
						  ':room_type_id'=>($room_type_id),
						  ':aldut_no'=>($aldut_no),
						  ':children'=>($children),
						  ':name'=>($name),
						  ':email'=>($email),
						  ':phone'=>($phone)
						 
						  ));
						  
						  $_SESSION['booked']=true;
		
	}



}

/**
 * 
 */
class Rooms{
	private $dbCon;
	
		public function __construct(){

		try{

		$this->dbCon = new Connection();

		$this->dbCon = $this->dbCon->dbConnection();
		$this->dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch (PDOException $e){
			echo "Lost connection to the database";
		}
	}



	public function addRoom($image_path, $name, $description,$room_type_id){

		  
				$date = DATE("Y-m-d h:i");
				$addNews = $this->dbCon->prepare("INSERT INTO rooms (name,image_url, description, room_type_id) VALUES (:name,:image_url, :description,:room_type_id)" );
				$addNews->execute(array(

						  ':name'=>($name),
						   ':image_url'=>($image_path),
						  ':description'=>($description),
						  ':room_type_id'=>($room_type_id)
						 
						  ));
						  
						  $_SESSION['room-added']=true;
		
	}
	
	public function getRoomType(){
		$getRoomType = $this->dbCon->PREPARE("SELECT id, name FROM room_type");
		$getRoomType->execute();

		if($getRoomType->rowCount()>0){
			$rows = $getRoomType->fetchAll();

			return  $rows;
		}
		
	}


		public function getRooms(){
		$getRooms = $this->dbCon->Prepare("SELECT rooms.id, rooms.name, rooms.description, rooms.image_url, room_type.name FROM rooms INNER JOIN room_type ON rooms.id = rooms.id");
		$getRooms->execute();
		
		if($getRooms->rowCount()>0){
			$rows = $getRooms->fetchAll();
			return $rows;
		}
	} //end of getting rooms
	
}


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
		FROM news INNER JOIN users ON (users.username=news.users_username) ORDER BY news.date_added DESC");
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
	
    /* public function getSpecificNews($id){
		$getSpecificNews = $this->dbCon->Prepare("SELECT id,title,news,image_url,date_added,users_username FROM news WHERE id=?");
		$getSpecificNews->bindParam(1,$id);
		$getSpecificNews->execute();
		
		if($getSpecificNews->rowCount()>0){
			$row = $getSpecificNews->fetch();
			return $row;
		}
		
	} */
}


class Prediction{
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

	public function getPayments(){
		$getPayments = $this->dbCon->Prepare("SELECT refNum,phone, amount, date_paid FROM payments");
		$getPayments->execute();
		
		if($getPayments->rowCount()>0){
			$rows = $getPayments->fetchAll();
			return $rows;
		}
	} //end of getting news
	
	public function getSpecificPayment($refNum){
		$getSpecificPayment = $this->dbCon->Prepare("SELECT refNum FROM refnumbers WHERE refNum=?");
		$getSpecificPayment->bindParam(1,$refNum);
		$getSpecificPayment->execute();
		
		if($getSpecificPayment->rowCount()>0){
			$row = $getSpecificPayment->fetch();
			return $row;
		}
	} //end of getting a specific news
	
	
	//add news
	public function addPaymentRef($refNum){
			
				$date_posted =DATE("Y-m-d H:i"); // active
				$addPaymentRef = $this->dbCon->prepare("INSERT INTO refnumbers (refNum,date_added) VALUES (:refNum,:date_added)" );
				$addPaymentRef->execute(array(
						  ':refNum'=>($refNum),
						  ':date_posted'=>($date_posted)
						  ));
						  
						  $_SESSION['payment-ref-added']=true;
		
	}
	
	public function placePrediction($winning_team,$payment_ref,$phone,$amount,$fixture_id){
		//check if payment exist
		$checkPayment = new Payments();
		$payment = $checkPayment->getSpecificPayment($payment_ref);
		
		if($payment !=null){
			
				//insert into payments
		$date_posted =DATE("Y-m-d H:i"); // active
		$placePrediction = $this->dbCon->prepare("INSERT INTO payments (refNum,phone,amount,date_paid,winning_team) VALUES (:refNum,:phone,:amount,:date_paid,:winning_team)" );
		$placePrediction->execute(array(
				  ':refNum'=>($payment_ref),
				  ':phone'=>($phone),
				  ':amount'=>($amount),
				  ':date_paid'=>($date_posted),
				  ':winning_team'=>($winning_team)
				  ));
		//insert into fixture has payments
		$addFixturePaymet = $this->dbCon->prepare("INSERT INTO fixture_has_payments (fixture_fixture_id,payments_refNum,payments_phone)
		VALUES (:fixture_fixture_id,:paymets_refNum,:payments_phone)" );
		$addFixturePaymet->execute(array(
				  ':fixture_fixture_id'=>($fixture_id),
				  ':paymets_refNum'=>($payment_ref),
				  ':payments_phone'=>($phone)
				  ));
				  $_SESSION['prediction-added']=true;
						  
		}else{
			
			$_SESSION['ref-not-found'] = true;
		}
	
		
	}
public function getWinner($fixture_id){
	//pull winning team from results
	$status = 3; // game has ended
	$getResults = $this->dbCon->PREPARE("SELECT home_team,home_team_score, away_team,away_team_score,fixture_id, results_id FROM results WHERE fixture_id =? AND status =?");
	$getResults->bindParam(1,$fixture_id);
	$getResults->bindParam(2,$status);
	$getResults->execute();
	if($getResults->rowCount()>0){
		$row = $getResults->fetch();
		
		if($row['home_team_score']>$row['away_team_score']){
				$winning_team = $row['home_team'];
			
		}else{
			$winning_team = $row['away_team'];
		}
		
		
	}

	//get all numbers with correct results
	$getWinnersFromPayments = $this->dbCon->PREPARE("SELECT payments_phone,payments_refNum as refNum FROM fixture_has_payments INNER JOIN payments ON(payments.refNum=fixture_has_payments.payments_refNum) WHERE fixture_fixture_id=? AND winning_team =? ORDER BY rand() LIMIT 1");
	$getWinnersFromPayments->bindParam(1,$fixture_id);
	$getWinnersFromPayments->bindParam(2,$winning_team);
	$getWinnersFromPayments->execute();
	
	if($getWinnersFromPayments->rowCount()>0){
		$row = $getWinnersFromPayments->fetch();
		
		$winner =$row['payments_phone'];		
		$refNum =$row['refNum'];
		
		
		
		//insert into winners
		$date_won =DATE("Y-m-d H:i"); // active
		$addWinner = $this->dbCon->PREPARE("INSERT INTO winners (fixture_fixture_id,payments_refNum,payments_phone,date_won) VALUES (:fixture_fixture_id,:payments_refNum,:payments_phone,:date_won)");
		$addWinner->execute(array(		
				  ':fixture_fixture_id'=>($fixture_id),
				  ':payments_refNum'=>($refNum),
				  ':payments_phone'=>($winner),
				  ':date_won'=>($date_won)
				  ));
				  $_SESSION['winner-found']=true;
		
		
		
		
		
	}
	
	
}

 
			
public function getWinnersList(){
	$getWinnersList = $this->dbCon->PREPARE("SELECT t.name as home_team, t2.name as away_team,
	payments_refNum as refNum,payments_phone as phone,date_won FROM winners INNER JOIN fixture ON (fixture.fixture_id=winners.fixture_fixture_id) INNER JOIN
			teams as t ON (t.id=fixture.home_team) INNER JOIN teams as t2
			ON(t2.id=fixture.away_team)");
	$getWinnersList->execute();
	
	if($getWinnersList->rowCount()>0){
		$rows = $getWinnersList->fetchAll();
		
		return $rows;
	}
	
}

	
}


?>