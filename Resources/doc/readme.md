1 -Run composer update to download the tika bundle

2- download tika.jar

3 - add these lines to config.yml, and change the parameters

funstaff_tika:
    tika_path:  /path/to/tika.jar   # required
    java_path:  ~                   # default: null
    metadata_class: ~               # default: Funstaff\Tika\Metadata
    output_format: ~                # default: xml
    metadata_only: ~                # default: false
    output_encoding: ~              # default: UTF-8
    logging: ~                      # default: prod = false, dev = true

configuration example :

funstaff_tika:
    tika_path:   %kernel.root_dir%/../web/tika.jar
    java_path: /home/nizare/environment/jdk1.6.0_45/bin/java                
    metadata_class: Funstaff\Tika\Metadata              
    output_format: xml 
    metadata_only: false
    output_encoding: UTF-8
    logging: ~ 

4- Create the upload folder under web forlder, this folder will used to store the uploaded files