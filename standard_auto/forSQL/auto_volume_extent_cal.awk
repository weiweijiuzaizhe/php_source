BEGIN{j="";volumetmp="";}
{
split($2,volume,",");
for(m in volume)
{
if(volume[m]!=null&&volume[m]!="-")
{volume[m]=volume[m]/1000;
volumetmp=volume[m]","volumetmp;
if(volume[m]<1.6){j="小排量";count[j]=count[j]+1;}
if(volume[m]>=1.6&&volume[m]<3){j="中排量";count[j]=count[j]+1;}
if(volume[m]>=3){j="大排量";count[j]=count[j]+1;}
}
}
for (j in count)
 {if(count[j]>maxfj){ maxfj=count[j];maxj=j; }
count[j]=0;
}
if (volumetmp==""){maxj="";}
printf $1"\t"maxj"\t"volumetmp"\t"$3"\t"$4"\t"$5"\t"$6"\n";

maxfj=0;volumetmp="";
}
