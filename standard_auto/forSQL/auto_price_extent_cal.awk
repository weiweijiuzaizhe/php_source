BEGIN{j="";max=0;min=10000;}
{
split($2,price,",");
for(m in price)
{
if(price[m]!=null)
{max = price[m] > max ? price[m] : max; 
min = price[m] < min ? price[m] : min;
if(price[m]<5){j="5万以下";count[j]=count[j]+1;}
if(price[m]>=5&&price[m]<15){j="5万-15万";count[j]=count[j]+1;}
if(price[m]>=15&&price[m]<25){j="15万-25万";count[j]=count[j]+1;}
if(price[m]>=25&&price[m]<35){j="25万-35万";count[j]=count[j]+1;}
if(price[m]>=35&&price[m]<45){j="35万-45万";count[j]=count[j]+1;}
if(price[m]>=45&&price[m]<55){j="45万-55万";count[j]=count[j]+1;}
if(price[m]>=55){j="55万以上";count[j]=count[j]+1;}
}
}
for (j in count)
 {if(count[j]>maxfj){ maxfj=count[j];maxj=j; }
count[j]=0;
}
printf $1"\t"maxj"\t"min"\t"max"\t"$2"\n";
max=0;min=10000;maxfj=0;
}
