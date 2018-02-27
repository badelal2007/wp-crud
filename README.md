# Basic example of wordpress plugin to select, update, insert and delete from database (CRUD)

# Documentation

# Instalation

Click on "Download ZIP" to download the example
After download OR clone git repository(git@github.com:badelal2007/wp-crud.git), go to this plugin directory and run below command

composer install

Includes the following files

*   **init** – plugin initialization, where everything is put toghether
*   **list** – showing a list of items
*   **update** – for updating and deleting items
*   **create** – for inserting new items
*   **style-admin.css** – stylesheet to use in the admin screens

## How to use the code

First, install and use the plugin **to understand how it works**:

*   Download the files
*   Unzip to wp-content/plugins folder
*   Create the table manually on the same wordpress database using the file _example-database.sql_ (you can use the phpmyadmin tool)
*   Activate the plugin

You will see a new administration menu "Press Room" on your left:

Take your time and perform all operations: _select, insert, update and delete_

## Customize the code

If yout take a look at the code you’ll see that every function has a prefix “**mmm_press_room**” and the name of the table “**PRESSROOM**“. This is because you need to create a namespace to avoid duplicated function names.

(The name “PRESSROOM” is the name of [my company](http://pressroom.com "pressroom.com") and “PRESSROOM” the name of the table to manage)

How to modify the code to manage another table:

*   **Replace** “mmm_press_room” with your company name and “PRESSROOM” with the table name
*   Replace the columns ID and NAME with your columns
*   Modify the html forms

To learn how to use the wordpress database functions: [http://codex.wordpress.org/Class_Reference/wpdb](http://codex.wordpress.org/Class_Reference/wpdb)

The code is written with the minimum to avoid complexity.

Remember that the purpose of this code is to help you build your own plugin with validations, style, proper messages, etc.


