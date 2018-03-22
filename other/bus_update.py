from bs4 import BeautifulSoup
from sys import argv
from urllib import parse
from urllib import request
import requests
import time
import pymysql
import re
import random

#获取时间
def get_time(start,end):
    last = '从%s到%s有多远' % (start,end)
    last_url = parse.quote_plus(last)
    url = 'http://juli.liecheshike.com/%s' % (last_url)
    html = requests.get(url).content.decode('utf-8')
    dis = re.findall(r'<h3>(.*)公里</h3>',html)
    if dis == None or len(dis) == 0:
        return 0
    time = round(int(dis[0]) / 80,1)
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
#获取站点id
def get_station_id(cursor,station):
    sql = "select id from station where name='" + station + "' and state=1"
    cursor.execute(sql)
    if cursor.rowcount > 0:
        result = cursor.fetchone()
        station_id = result[0]
        return station_id
    return False
#如果站点不存在,添加
def set_station_id(cursor,station):
    sql = "insert into station(name) values('%s')" % (station)
    cursor.execute(sql)
    sql = "select id from station where name='%s' and state=1" % (station)
    cursor.execute(sql)
    result = cursor.fetchone()
    return str(result[0])
#获取线路信息
def get_result_from_114(start,end,date,re_time,cursor):
    #获取出发站点、目的站点、日期id
    start_station_id = get_station_id(cursor,start)
    end_station_id = get_station_id(cursor,end)
    #获取主要信息
    url = 'http://qiche.114piaowu.com/qicheZdz_searchAdapter.action'
    browsers = ['Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36 Edge/15.15063','Mozilla/5.0(Macintosh;U;IntelMacOSX10_6_8;en-us)AppleWebKit/534.50(KHTML,likeGecko)Version/5.1Safari/534.50','Mozilla/5.0(WindowsNT6.1;rv:2.0.1)Gecko/20100101Firefox/4.0.1','Opera/9.80(Macintosh;IntelMacOSX10.6.8;U;en)Presto/2.8.131Version/11.11','Mozilla/4.0(compatible;MSIE7.0;WindowsNT5.1;Maxthon2.0)','Mozilla/4.0(compatible;MSIE7.0;WindowsNT5.1;TheWorld)','Mozilla/4.0(compatible;MSIE7.0;WindowsNT5.1;360SE)']
    i = random.randint(0,6)
    h_lines = ['shanghai-beijing','changsha-guangzhou','beijing-sihecun','changsha-qiyang','guangzhou-shenzhen','guangzhou-qiyangxian','changsha-yongzhou']
    headers = {
        'User-Agent':browsers[i],
        'Host':'qiche.114piaowu.com',
        'Origin':'http://qiche.114piaowu.com',
        'Referer':'http://qiche.114piaowu.com/%s' % (h_lines[i]),
        'X-Requested-With':'XMLHttpRequest'
    }
    data = {
        'startStation':start,
        'endStation':end,
        'goDate':date
    }
    proxies = [{'http':'169.254.195.159:80'},{'http':'169.254.25.181:80'}]
    html = requests.post(url,headers = headers,data = data,proxies = proxies[i%2]).content.decode('utf-8')
    if html.find('系统错误页面') != -1 or html.find('暂未查询到汽车票数据') != -1:
        print(start + " ---> " + end + "\t无数据",end = '\n')
        sql = "select * from city where city like '%" + start + "%' and state=1"
        cursor.execute(sql)
        city = cursor.fetchone()
        if city != None:
            city_id = city[0]
            sql = "update city_to_station set state=0 where city_id=%d and station_id=%d" % (city_id,end_station_id)
            cursor.execute(sql)
        return False
    soup = BeautifulSoup(html,'lxml')
    results = soup.find_all('ul',class_ = 'content')
    sql = u"update line set state=0 where start_station_id=%s and end_station_id=%s" % (start_station_id,end_station_id)
    cursor.execute(sql)
    for result in results:
        lis = result.find_all('li')
        start_time = lis[0].text.replace(' ','')
        start_station = lis[1].find('p',class_ = 's').text.replace(' ','')
        end_station = lis[1].find('p',class_ = 'z').text.replace(' ','')
        type_ = re.sub(r'[\n\r\t><]','',lis[2].text.replace(' ',''))
        price = lis[3].text
        arrive_time = get_arrive_time(start_time,re_time)
        sql = u"select * from line where start_station_id=%s and end_station_id=%s and start_time='%s' and start_station_name='%s' and end_station_name='%s'" % (start_station_id,end_station_id,start_time,start_station,end_station)
        cursor.execute(sql)
        rows = cursor.rowcount
        #如果存在,更新
        if rows > 0:
            sql = u"update line set state=1,date_time='%s',price=%s where start_time='%s' and arrive_time='%s' and time=%s" % (time.strftime('%Y-%m-%d %H:%M:%S'),price,start_time,arrive_time,re_time)
        #不存在,插入
        else:
            sql = u"insert into line(start_station_id,end_station_id,start_station_name,end_station_name,start_time,arrive_time,time,type,price) values(%s,%s,'%s','%s','%s','%s',%s,'%s','%s')" % (start_station_id,end_station_id,start_station,end_station,start_time,arrive_time,re_time,type_,price)
        cursor.execute(sql)
    print(start + " ---> " + end + "\t已更新",end = '\n')
    return True

def get_result_from_xiecheng(start,end,date,re_time,cursor):
    #获取出发站点、目的站点、日期id
    start_station_id = get_station_id(cursor,start)
    end_station_id = get_station_id(cursor,end)
    url = 'http://bus.ctrip.com/busListn.html?from=%s&to=%s&date=%s' % (parse.quote_plus(start),parse.quote_plus(end),date)
    html = requests.get(url).content.decode('utf-8')
    if html.find('系统错误页面') != -1 or html.find('您可以试试更改搜索条件重新搜索，或选择中转') != -1:
        print(start + " ---> " + end + "\t无数据",end = '\n')
        sql = "select * from city where city like '%" + start + "%' and state=1"
        cursor.execute(sql)
        city = cursor.fetchone()
        if city != None:
            city_id = city[0]
            sql = "update city_to_station set state=0 where city_id=%d and station_id=%d" % (city_id,end_station_id)
            cursor.execute(sql)
        return False
    soup = BeautifulSoup(html,'lxml')
    table = soup.find('table',class_ = 'tb_railway_list nolayout')
    trs = table.find_all('tr')
    trs = trs[3:]
    for tr in trs:
        tds = tr.find_all('td')
        start_time = re.sub(r'[\n\r\t]','',tds[0].find('span',class_ = 'railway_time').text.replace(' ',''))
        arrive_time = get_arrive_time(start_time,re_time)
        stations = tds[1].text.replace(' ','').split('\n')[1:]
        type_ = tds[2].text.replace(' ','').split('\n')[1:3][0]
        price = re.sub(r'[\n\r\t¥]','',tds[3].text.replace(' ',''))
        sql = u"select * from line where start_station_id=%s and end_station_id=%s and start_time='%s' and start_station_name='%s' and end_station_name='%s'" % (start_station_id,end_station_id,start_time,stations[0],stations[1])
        cursor.execute(sql)
        rows = cursor.rowcount
        #如果存在,更新
        if rows > 0:
            sql = u"update line set state=1,date_time='%s',price=%s where start_time='%s' and arrive_time='%s' and time=%s" % (time.strftime('%Y-%m-%d %H:%M:%S'),price,start_time,arrive_time,re_time)
        #不存在,插入
        else:
            sql = u"insert into line(start_station_id,end_station_id,start_station_name,end_station_name,start_time,arrive_time,time,type,price) values(%s,%s,'%s','%s','%s','%s',%s,'%s','%s')" % (start_station_id,end_station_id,stations[0],stations[1],start_time,arrive_time,re_time,type_,price)
        cursor.execute(sql)
    print(start + " ---> " + end + "\t已更新",end = '\n')

def get_data(station1,station2,date,cursor):
    #获取出发地到目的地需要的时间
    re_time = get_time(station1,station2)
    get_result_from_114(station1,station2,date,re_time,cursor)
    get_result_from_xiecheng(station2,station1,date,re_time,cursor)
    time.sleep(random.uniform(2,3))

def main():
    db = pymysql.connect('localhost','root','','project',charset =  "utf8")
    cursor = db.cursor()
    sql = u"select * from city where state=1"
    cursor.execute(sql)
    date = '2018-03-19'
    if cursor.rowcount > 0:
        citys = cursor.fetchall()
        f = open('1.txt','r')
        num = f.readline().split(',',1)
        f.close()
        num1 = int(num[0])
        num2 = int(num[1])
        flag = True
        i = 0
        for city in citys:
            if city[0] <= num1:
                continue
            print(city[0])
            sql = u"select * from city_to_station where city_id=%d and state=1" % (city[0])
            cursor.execute(sql)
            if cursor.rowcount > 0:
                responsibles = cursor.fetchall()
                for responsible in responsibles:
                    if responsible[0] != num2 and flag:
                        continue
                    else:
                        flag = False
                    sql = u"select * from station where id=%d" % (responsible[2])
                    cursor.execute(sql)
                    station = cursor.fetchone()
                    station2 = station[1]
                    spare = city[2][len(city[2]) - 1:len(city[2])]
                    if spare == '市' or spare == '县' or spare == '区':
                        station1 = city[2][0:len(city[2]) - 1]
                    else: station1 = city[2]
                    if station2.find(station1) >= 0:
                        continue
                    get_data(station1,station2,date,cursor)
                    f = open('1.txt','w')
                    f.write(str(city[0] - 1) + ',' + str(responsible[0]))
                    f.close()

if __name__ == '__main__':
    main()