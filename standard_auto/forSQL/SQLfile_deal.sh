#!/bin/sh
mysqlclient=/var/lib/mysql/bin/mysql
database=mediavolap
dbuser=lijue
dbpass=lijuemediav
dbhost=research.rw.prod.mediav.com
dbport=30017
characterset=gbk
cubecsvpath=/data/output

maxMVid=`cat /home/lijue/spider/autocar/standard_auto/module/MVid_rawmodename.dat|awk 'BEGIN{max=0;}{if($1>max)max=$1;}END{print max;}'`
echo $maxMVid;


#对已有id对应的项使用local表将mv三张表更新
datadir="/home/lijue/spider/autocar/standard_auto/data/"
siteid=578
date=20120507

#主表更新,使用汽车之家最新的厂商，品牌，modename，级别，上市状态
#cat $datadir"/"$siteid"/"$date"/local_"$siteid|cut -f1,5-8,10,14 > oldMVidupdate_Auto_Dim_AutoMode.dat

updateday=`date +%F`
updatehour=`date "+%H:00:00"`

#价格表更新，根据汽车之家有价格的给update最新价
#cat $datadir"/"$siteid"/"$date"/local_"$siteid|cut -f1,15|sed 's/万//g'|sed 's/~/,/g'|awk '{if($2!=null)print $0;}'|awk -f auto_price_extent_cal.awk|awk -F "\t" -v updateday=$updateday -v updatehour=$updatehour '{print $0"\t官方出厂价格\t"updateday" "updatehour;}' > oldMVidupdate_Auto_Dim_AutoPriceRange.dat

#排量表更新
#cat $datadir"/"$siteid"/"$date"/local_"$siteid|cut -f1,16,17,18,19|sed 's/ml//g'|awk '{if($2!=null)print $0;}'|awk -f auto_volume_extent_cal.awk > oldMVidupdate_Auto_Dim_AutoPerformance.dat


#新入库文件配ID

newid=$((maxMVid+1))
echo $newid;
cat $datadir"/"$siteid"/"$date"/rfornewid_"$siteid|awk -v newid=$newid '{print newid"\t"$0;newid=newid+1;}' > $siteid"_newidcandidate.dat"

cat $siteid"_newidcandidate.dat"|cut -f1,4-7,9,13 > newMVidinsert_Auto_Dim_AutoMode.dat
#insert into Auto_Dim_AutoMode (ID,AutoMakeCHN_S, AutoFactoryCHN_S, AutoBrandCHN, AutoModeCHN, AutoBodyTypeCHN, AutoMakeType, AutoSaleStatus, Mark) values ("1686","雷诺","雷诺","雷诺","塔利斯曼","中大型车","进口","在售","0")

cat $siteid"_newidcandidate.dat"|cut -f1,14|sed 's/万//g'|sed 's/~/,/g'|awk '{if($2!=null)print $0;}'|awk -f auto_price_extent_cal.awk|awk -F "\t" -v updateday=$updateday -v updatehour=$updatehour '{print $0"\t官方出厂价格\t"updateday" "updatehour;}' > newMVidinsert_Auto_Dim_AutoPriceRange.dat
cat newMVidinsert_Auto_Dim_AutoPriceRange.dat|awk -F "\t" -v var="','" -v front="('" -v varend="'),"  '{print front$1var$2var$3var$4var$5var$6var$7varend}'|tr -d '\n'|awk '{print substr($0,1,length($0)-1)";"}'|awk '{print "insert into mediavolap.Auto_Dim_AutoPriceRange (AutoModeID, PriceRange, MinPrice, MaxPrice, RawPrice, PriceSource, Updatetime) values " $0}' > readyforinsert.tmp

cat $siteid"_newidcandidate.dat"|cut -f1,15,16,17,18|sed 's/ml//g'|awk '{if($2!=null)print $0;}'|awk -f auto_volume_extent_cal.awk > newMVidinsert_Auto_Dim_AutoPerformance.dat
#insert into mediavolap.Auto_Dim_AutoPerformance (AutoModeID, EngineSizeRange, EngineSize, FuelType, Transmission, Doors)
#values ("1685","小排量","1.497,1.497,1.497,","93号汽油:3","5挡手动:3","4门5座三厢车:3"),("1686","中排量","2.495,2.495,2.495,3.498,3.498,","93号汽油:3,97号汽油:2","6挡手自一体:5","4门5座三厢车:5");




#local表新记录插入
#cat $datadir"/"$siteid"/"$date"/ExmvidVsNewlocalid_"$siteid|awk -v var="','" -v front="('" -v varend="'),"  '{print front dtdate var"1"var$2var$1var$3varend}'|tr -d '\n'|awk '{print substr($0,1,length($0)-1)";"}'|awk '{print "insert into data_study_interest_olap (date, modelid, topicid, adspaceid, olapcookie) values " $0}' > readyforinsert.tmp

#cat $siteid"_newidcandidate.dat"|awk -v var="','" -v front="('" -v varend="'),"  '{print front dtdate var"1"var$2var$1var$3varend}'|tr -d '\n'|awk '{print substr($0,1,length($0)-1)";"}'|awk '{print "insert into data_study_interest_olap (date, modelid, topicid, adspaceid, olapcookie) values " $0}' > readyforinsert.tmp


#cat readyforinsert.tmp| while read cmd
 #                            do
  #                         echo $cmd | $mysqlclient -u $dbuser -p$dbpass -h $dbhost -P $dbport --database=$database --default-character-set=$characterset
  #                         done