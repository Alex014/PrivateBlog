# PrivateBlog
Private blog agregator for emercoin blockchain

This is a blog aggregator. All posts are stored in emercoin blockchain. The posts can be either anonymous or have anonymous blogger as an author. The posts can also reply to each other. The posts can be created, updated or deleted only by an owner of emercoin wallet using name-value storage (NVS).

The blockchain values from NVS gets parsed by this aggregator using @key="value" params inside the NVS value.

## Register a blogger (optional)
1. Create a record named blogger:username in emercoin NVS
2. Add optional value as a user description. 

* @key emercoin address (example ENwm9Aq8vHgTW6akyti3vQSZJK2qPAGaYW)
* @sig the bloggers signature, result of signmessage "emercoinaddress" "username" command from emercoin console or RPC

## Make a post
1. Create a record named blog:postname in emercoin NVS
2. The value will be the post body.
* The body can contain all HTML tags except < script > tag 

* @title title of a post (optional)
* @lang the ISO_639-1 code of post language (optional, default en)
* @username username of blogger (optional)
* @sig (optional used with @username) 
The result of signmessage "emercoinaddress" "username:postname" command, where emercoinaddress is @key from user's record and postname is this post name from blog:postname. This signature gets verified by verifymessage "emercoinaddress" "@sig" "username:postname" command
* @keywords "drugs,sex,rockandroll" (optional)
* @reply the name of the post you want to reply to (optional)

## Make a reply to other post
* Any post can reply to any other post using @reply keyword 
* @reply the name of the post you want to reply to

## Links to other posts
 #post_name_link_to="link caption"

## Post, that includes other posts or post > 20kb
Inside post: %%subpost_name%%
Max level of subposts is 5

## Big files
 Large files > 20kb is divided into parts

 Execute console commands:
1. split --bytes=18k Konrad_Curze_sketch_small.jpg
2. md5sum Konrad_Curze_sketch_small.jpg
3. file -b --mime-type Konrad_Curze_sketch_small.jpg
 
 Create NVS records

* file:file_hash (file:3d9c09df0d72f0a3b02d03a89d2ee5b1)
* {"content_type": "image/jpeg", "name": "Konrad_Curze_sketch_small.jpg", "parts": 5}

* file:file_hash:1 (file:3d9c09df0d72f0a3b02d03a89d2ee5b1:1) - part 1 of file Konrad_Curze_sketch_small.jpg
 ...
* file:file_hash:5 - part 5
 

 $$$hash_of_file - url to file
 $hash_of_file="link caption" - link to file

## Links
* Site http://privateblog.net
* Test page http://104.129.0.142/ (login:blog password:private)
* Emercoin http://emercoin.com/
* Market cap http://coinmarketcap.com/currencies/emercoin/#markets
* Forum (en) https://bitcointalk.org/index.php?topic=362513.0
* Forum (ru) https://forum.bits.media/index.php?/topic/3408-emc-emercoin-pos/

###### writen in PHP
###### darkblog.sql - mysql database
###### cron.php - completely eraises all data and agregates new data from blockchain (run by cron)

## Uses

###### MeekroDB http://meekro.com/
###### Bootstrap http://getbootstrap.com/