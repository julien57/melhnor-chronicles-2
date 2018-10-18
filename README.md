# Melhnor Chronicles 2

Melhnor Chronicles 2 is online multiplayer strategy game in a heroic fantasy universe. 
The game use Symfony 3.4.

## Stack technique

* Symfony 3.4
* PHP 7.1
* Web-server (Apache)
* Database (MySQL)
* Cron Task (for call command "game:add-action-points" each hour)

## PHP extensions

* intl
* XDebug

## Features

### Anonymous
* Creation for player account
* Contact admin
* Read game rules

### Playerserver
* Login to your account
* Build buildings in his kingdom and level up
* Produce resources based on buildings
* Sell and buy resources at market
* Read and write messages to other players

### Administrator
* Access to dashboard (count of players, list of players,..)
* Player card for watch all stats at players
* Remove a player
* Read to messages

## Installation

* First, go to a directory where you want to install the project. For exemple: '/my_project':

 ```bash
 cd /my_project
 git clone git@github.com:julien57/melhnor-chronicles-2.git
 ```
 
 * Installation of dependencies:
 
 ```bash
 cd melhnor-chronicles-2
 composer update
 ```
 
 Les fichiers contenus dans les dossiers backend-assets et frontend-assets sont gérés avec GULP (JS, CSS, img).
 
 * Migrations:
  ```bash
  bin/console doctrine:database:create
  bin/console doctrine:migrations:migrate
  ```
  
 Now, we can use the commands for create buildings, regions, avatars and resources.
 
 ```bash
   bin/console game:create-resources
   bin/console game:create-buildings
   bin/console game:create-avatars
   bin/console game:create-regions
   ```
   
   **The game can finally start !**
   
   ## Code styles
   
   * For PHP, the synthax is managed by `php-cs-fixer`:
   ```bash
   make cs-fix
   ```
   
   ## GULP for frontend
   The site use GULP for backend assets (JS, images, css) in folders `frontend-assets` and `backend-assets`. Each command 
   to use GULP send files in the /web folder. For example `gulp js`, `gulp images` or `gulp css`
   
   
   ## GIT Flow
   
   2 branchs are active, `develop` and `master`:
   * `develop`: It's a default branch for work. When the work is done, merge in `master`
   * `master`: this branch contain a last version of the site. The version of production.
