from bs4 import BeautifulSoup
from sys import argv
from urllib import parse
import requests
import time
import pymysql
import re
# import os
# import psutil

starts = []
ends = []
start_times = []
types = []
prices = []
#获取时间
def get_time(start,end):
    start_url = parse.quote_plus(start)
    end_url = parse.quote_plus(end)
    url = 'http://api.map.baidu.com/?qt=nav&c=131&sn=2%24%24%24%24%24%24' + start_url + '%24%24%24%24&en=2%24%24%24%24%24%24' + end_url + '%24%24%24%24&sy=0&ie=utf-8&oue=1&res=api&callback=BMap._rd._cbk3041'
    html = requests.get(url).content.decode('utf-8')
    if html.find("{\"content\":{\"dis\":") != -1:
        dis = int(re.findall(r'{\"content\":{\"dis\":(.*),\"kps\"',html)[0]) / 1000
    else: return 0
    time = round(dis / 80,1)
    return time
#获取到达时间
def get_arrive_time(start_time,time):
    h = int(start_time[0:2])
    m = round(int(start_time[3:5]) / 60,2)
    start_time = h + m
    arrive_time = start_time + time
    if arrive_time >= 24:
        arrive_time -= 24
    h = str(int(arrive_time))
    m = int((arrive_time - int(arrive_time)) * 60)
    if m < 10:
        m = '0' + str(m)
    else: m = str(m)
    return h + ':' + m
#获取线路信息
def get_result(url,start,end,date):
    headers = {
        'User-Agent':'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
        'Host':'qiche.114piaowu.com',
        'Origin':'http://qiche.114piaowu.com',
        'Referer':'http://qiche.114piaowu.com/changsha-guangzhou',
        'X-Requested-With':'XMLHttpRequest'
    }
    data = {
        'startStation':start,
        'endStation':end,
        'goDate':date
    }
    html = requests.post(url,headers = headers,data = data).content.decode('utf-8')
    if html.find('系统错误页面') != -1 or html.find('暂未查询到汽车票数据') != -1:
        print('暂未查询到汽车票数据')
        return False
    soup = BeautifulSoup(html,'lxml')
    results = soup.find_all('ul',class_ = 'content')
    for result in results:
        lis = result.find_all('li')
        start_time = lis[0].text.replace(' ','')
        start = lis[1].find('p',class_ = 's').text.replace(' ','')
        end = lis[1].find('p',class_ = 'z').text.replace(' ','')
        type_ = re.sub(r'[\n\r\t><]','',lis[2].text.replace(' ',''))
        price = lis[3].text
        start_times.append(start_time)
        starts.append(start)
        ends.append(end)
        types.append(type_)
        prices.append(price)
    return True
#获取出发站点id
def get_start_station_id(cursor,start):
    sql = "select id from station where name='" + start + "' and state=1"
    cursor.execute(sql)
    if cursor.rowcount > 0:
        result = cursor.fetchone()
        start_station_id = str(result[0])
        return start_station_id
    return False
#获取目的站点id
def get_end_station_id(cursor,end):
    sql = "select id from station where name='" + end + "' and state=1"
    cursor.execute(sql)
    if cursor.rowcount > 0:
        result = cursor.fetchone()
        end_station_id = str(result[0])
        return end_station_id
    return False
#如果站点不存在,添加
def set_station_id(cursor,station):
    sql = "insert into station(name) values('%s')" % (station)
    cursor.execute(sql)
    sql = "select id from station where name='%s' and state=1" % (station)
    cursor.execute(sql)
    result = cursor.fetchone()
    return str(result[0])
#获取数据
def update(start,end,date,cursor):
    #获取出发地到目的地需要的时间
    re_time = get_time(start,end)
    #获取出发站点、目的站点、日期id
    start_station_id = get_start_station_id(cursor,start)
    end_station_id = get_end_station_id(cursor,end)
    url = 'http://qiche.114piaowu.com/qicheZdz_searchAdapter.action'
    result = get_result(url,start,end,date)
    if result == False:
        sql = "select * from city where city like '%" + start + "%' and state=1"
        cursor.execute(sql)
        city_id = str(cursor.fetchone()[0])
        sql = "update city_to_station set state=0 where city_id=%s and station_id=%s" % (city_id,end_station_id)
        cursor.execute(sql)
        return 0
    #如果不存在,添加
    if start_station_id == False:
        print('没有该站点信息')
        return 0
    if end_station_id == False:
        print('没有该站点信息')
        return 0
    for i in range(0,len(starts)):
        arrive_time = get_arrive_time(start_times[i],re_time)
        #检查线路信息是否存在
        sql = u"select * from line where start_station_id=%s and end_station_id=%s and start_time='%s' and start_station_name='%s' and end_station_name='%s'" % (start_station_id,end_station_id,start_times[i],starts[i],ends[i])
        cursor.execute(sql)
        rows = cursor.rowcount
        #如果存在,更新
        if rows > 0:
            sql = u"update line set state=1,date_time='" + time.strftime('%Y-%m-%d %H:%M:%S') + "',price='" + prices[i] + "',start_time='" + start_times[i] + "' where start_time='" + start_times[i] + "'" + " and arrive_time='" + arrive_time + "' and time=" + str(re_time) + ""
        #不存在,插入
        else:
            sql = "insert into line(start_station_id,end_station_id,start_station_name,end_station_name,start_time,arrive_time,time,type,price) values(%s,%s,'%s','%s','%s','%s',%s,'%s','%s')" % (start_station_id,end_station_id,starts[i],ends[i],start_times[i],arrive_time,re_time,types[i],prices[i])
        cursor.execute(sql)
        result = start_times[i] + ',' + arrive_time + ',' + str(int(re_time)) + '时' + str(int(round(re_time - int(re_time),2) * 60)) + '分' + ',' + starts[i] + ','\
                + ends[i] + ',' + types[i] + ',' + prices[i] + ';'
        print(result)

def main(start,end,date,host,user,pwd,db_name):
    db = pymysql.connect(host,user,pwd,db_name,charset =  "utf8")
    cursor = db.cursor()
    update(start,end,date,cursor)

if __name__ == '__main__':
    time.sleep(1)
    name,start,end,date,host,user,pwd,db_name = argv
    main(start,end,date,host,user,'',db_name)