CREATE TABLE `Auto_Fact_AutoMode_AutoSite_temp` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `AutoModeID` int(20) DEFAULT NULL COMMENT 'Mediav车型ID',
  `SiteID` int(20) DEFAULT NULL COMMENT '网站publisherid',
  `SiteAutoModeID` varchar(255) DEFAULT NULL COMMENT '网站车型ID',
  `AutoMakeCHN_S` varchar(20) DEFAULT NULL COMMENT '汽车制造商中文（缩写）',
  `AutoFactoryCHN_S` varchar(20) DEFAULT NULL COMMENT '汽车制造厂中文（缩写）',
  `AutoBrandCHN` varchar(20) DEFAULT NULL COMMENT '汽车品牌中文',
  `AutoModeCHN` varchar(256) DEFAULT NULL COMMENT '汽车型号中文',
  `AutoLaunchYear` int(4) DEFAULT NULL COMMENT '上市年份',
  `AutoBodyTypeCHN` varchar(20) DEFAULT NULL COMMENT '中国车型划分:微型，小型，紧凑型，中型，中大型，豪华型，跑车，SUV，MPV',
  `AutoStyle` varchar(10) DEFAULT NULL COMMENT '汽车风格：本土，美系，日系，德系，韩系，法系，意系，北欧系，俄系，其他，未知',
  `AutoMakeType` varchar(10) DEFAULT NULL COMMENT '制造属性：国产，进口，合资，未知',
  `AutoMarket` varchar(20) DEFAULT NULL COMMENT '销售市场：全球，中国，未知',
  `AutoSaleStatus` varchar(10) DEFAULT NULL COMMENT '销售状态：上市，停产，未上市，概念车',
  `AutoRawPrice` varchar(1028) DEFAULT NULL COMMENT '网站车型的价格',
  `AutoEngineSize` varchar(1028) DEFAULT NULL COMMENT '网站车型耗油量大小',
  `AutoFuelType` varchar(256) DEFAULT NULL COMMENT '车型的燃料类型',
  `AutoTransmission` varchar(256) DEFAULT NULL COMMENT '变速箱类型',
  `AutoDoors` varchar(256) DEFAULT NULL COMMENT '车门数',
  `AutoSize` varchar(256) DEFAULT NULL COMMENT '车身尺寸',
  `AutoPic` varchar(1028) DEFAULT NULL COMMENT '车型图片',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=12651 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;


在自己的虚拟机上安装mysql
yum install -y mysql
yum install -y mysql-server
yum install -y mysql-devel
chgrp -R mysql /var/lib/mysql
chmod -R 770 /var/lib/mysql

进入mysql：
GRANT ALL PRIVILEGES ON *.* TO wuwei@'%';

netstat -ltnp|grep 3306查看是否监听着端口

iptables -nL查看防火墙情况
/etc/init.d/iptables stop关闭防火墙
chkconfig --level 2345 iptables off让防火墙不要开机启动

以服务方式启动mysqld
service mysqld start


这样，在91上执行  /var/lib/mysql/bin/mysql -h192.168.3.144 -uwuwei -P3306也可以登录到本地虚拟机上的mysql了

导表方法：
mysqldump -hdb6sh.prod.mediav.com -uwuwei -p$(cat /home/wuwei/password/mysql_pwd.txt) -P30017  mediavolap Auto_Dim_AutoURL --lock-tables=false|/var/lib/mysql/bin/mysql -h192.168.3.144 -uwuwei -P3306 -Dmediavolap



在91上把汽车相关的内容导出到本地
db6 -e"show tables in mediavolap like '%Auto%' ;" -N|awk '{print $0}'|awk '{printf("mysqldump -hdb6sh.prod.mediav.com -uwuwei -p$(cat /home/wuwei/password/mysql_pwd.txt) -P30017  mediavolap %s --lock-tables=false|/var/lib/mysql/bin/mysql -h192.168.3.144 -uwuwei -P3306 -Dmediavolap;\n",$1)}'



select * from mediavolap.Auto_Dim_AutoMode limit 10; 这张表中是每个车系的情况

 alter table Auto_Fact_AutoMode_AutoSite drop column SiteName;
 select * from Auto_Fact_AutoMode_AutoSite limit 10;这张表中是每个车系在不同网站的情况。
 
 
 
 update Auto_Fact_AutoMode_AutoSite set site_name='car.bitauto.com' where SiteID = 622;
 update Auto_Fact_AutoMode_AutoSite set site_name='xcar.com.cn' where SiteID =  27;
 update Auto_Fact_AutoMode_AutoSite set site_name='pcauto.com.cn' where SiteID =  102;
 update Auto_Fact_AutoMode_AutoSite set site_name='auto.qq.com' where SiteID =  107;
 update Auto_Fact_AutoMode_AutoSite set site_name='auto.163.com' where SiteID =  118;
 update Auto_Fact_AutoMode_AutoSite set site_name='auto.sina.com' where SiteID =  130;
 update Auto_Fact_AutoMode_AutoSite set site_name='auto.sohu.com' where SiteID =  98;
 update Auto_Fact_AutoMode_AutoSite set site_name='auto.ifeng.com' where SiteID =  49;
 update Auto_Fact_AutoMode_AutoSite set site_name='autohome.com.cn' where SiteID =  578;
 update Auto_Fact_AutoMode_AutoSite set site_name='www.52che.com' where SiteID =  631;
 
 select * from Auto_Dim_AutoURL limit 10;
 
 
 
 mysql> select * from Auto_Dim_AutoURL limit 2\G
*************************** 1. row ***************************
                 ID: 1
         AutoModeID: 1
             SiteID: 578
     SiteAutoModeID: 1004
 SiteAutoBodyTypeID: a00
   SiteAutoModelURL: http://www.autohome.com.cn/1004/
SiteAutoBodyTypeURL: http://www.autohome.com.cn/a00/
            AddTime: 2011-12-19 10:25:00
         UpdateTime: 2011-12-19 10:25:00
*************************** 2. row ***************************
                 ID: 2
         AutoModeID: 2
             SiteID: 578
     SiteAutoModeID: 155
 SiteAutoBodyTypeID: a00
   SiteAutoModelURL: http://www.autohome.com.cn/155/
SiteAutoBodyTypeURL: http://www.autohome.com.cn/a00/
            AddTime: 2011-12-19 10:25:00
         UpdateTime: 2011-12-19 10:25:00
	
所有的参与训练的url	
select * from Auto_Dim_AutoUrlTrainning limit 10;




		 
		 

		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 
		 

