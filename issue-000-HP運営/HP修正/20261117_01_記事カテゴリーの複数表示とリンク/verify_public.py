from concurrent.futures import ThreadPoolExecutor
from html.parser import HTMLParser
from pathlib import Path
import subprocess

BASE = 'https://www.inabu-jaburo.jp'
INTRO = '稲武で暮らし、働く。まずは自己紹介から'
GUN = 'このプロジェクトの「ガンタンク」とは？'
class Page(HTMLParser):
    def __init__(self, html):
        super().__init__(); self.stack=[]; self.links=[]; self.active=None; self.feed(html); self.html=html
    def handle_starttag(self, tag, attrs):
        a=dict(attrs)
        if tag=='a': self.active={'href':a.get('href',''),'text':'','classes':' '.join(x[1] for x in self.stack)}
        if tag not in ['img','meta','link','br','hr','input','source','wbr']: self.stack.append((tag,a.get('class','')))
    def handle_data(self,data):
        if self.active is not None: self.active['text']+=data
    def handle_endtag(self,tag):
        if tag=='a' and self.active is not None: self.links.append(self.active); self.active=None
        for i in range(len(self.stack)-1,-1,-1):
            if self.stack[i][0]==tag: self.stack=self.stack[:i]; break
    def labels(self,cls): return [(a['text'].strip(),a['href']) for a in self.links if cls in a['classes']]

urls={'intro':'/?p=265','gun':'/?p=267','all':'/report/','small':'/report/report_category/small-hydro-power-generation/','animal':'/report/report_category/animal-damage-measures/','rural':'/report/report_category/%e7%94%b0%e8%88%8e%e6%9a%ae%e3%82%89%e3%81%97/'}
def fetch(item):
    k,path=item
    html=subprocess.run(['curl','--fail','-sL',BASE+path],capture_output=True,text=True,check=True).stdout
    assert html and 'Fatal error' not in html, k
    return k,Page(html)
pages=dict(ThreadPoolExecutor(max_workers=4).map(fetch,urls.items()))
checks=0
def check(ok,label):
    global checks
    assert ok,label
    checks+=1;print('PASS '+label)
check([x[0] for x in pages['intro'].labels('gw-report__head-meta-category')]==['小水力発電','獣害対策','田舎暮らし'],'intro: all 3 category links')
check([x[0] for x in pages['gun'].labels('gw-report__head-meta-category')]==['獣害対策'],'gun: single category link')
check(len(pages['all'].labels('gw-report__archive-item-category'))==4,'archive: all 4 assigned category links')
for key,expected in [('all',[GUN,INTRO]),('small',[INTRO]),('animal',[GUN,INTRO]),('rural',[INTRO])]:
    check([x[0] for x in pages[key].labels('gw-report__archive-item-title')]==expected,key+': correctly filtered titles')
for key in ['intro','gun','all']:
    cls='gw-report__head-meta-category' if key!='all' else 'gw-report__archive-item-category'
    check(all('/report/report_category/' in href for _,href in pages[key].labels(cls)),key+': canonical category URLs')
check('はじめまして。よろづシステムズの古橋です。' in pages['intro'].html,'intro: opening retained')
check('このプロジェクトのタイトルに' in pages['gun'].html and '今、そんな感じでございます。' in pages['gun'].html,'gun: opening and ending retained')
check(any(x['text']=='いなぶラジオ(仮)を聴く（YouTube）' and 'PLJsEU8NkjZFMEdguHfRm1dMPY1bPyd-im' in x['href'] for x in pages['gun'].links),'gun: radio link retained')
check(all('2026.11.17' in pages[k].html for k in ['intro','gun','all']),'original publication dates retained')
print(f'PASS {checks} public integration checks')
