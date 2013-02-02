#!/usr/bin/python
# Evaluate thinned piping
from __future__ import division
import json;
import cgi;


def my_float(val):
	if(val=="-"):
		return 0;
	else:
		return float(val);
		
def calculateLevel1(param):
	l=my_float(param['l']);
	t=my_float(param['t']);
	d=my_float(param['d']);
	dia=my_float(param['dia']);
	Sflow=my_float(param['Sflow']);
	Equation="";
	Equation+="|n|z=\\frac{L^2}{D+t}=\\frac{Lcalc^2}{diacalc\\timestcalc}=Zanswer |n|";
#
	z=l**2.0/(dia*t);

	Equation=Equation.replace("Zanswer",str(z));
	if(param['Method']=='Original'):

		Equation+="M=\\sqrt{1+0.8z}=\\sqrt{1+0.8\\timesZanswer}=Manswer |n|";		
		M=float((1.0 + 0.8*z)**0.5);

		if(z<=20.0):
			Equation+="z\\leq20 |n|";
			Sf=Sflow*(1.0 - (2.0/3.0)*(d/t))/(1 - (2.0/3.0)*(d/t)/M);	
			Equation+="S_f}=S_{flow}\\times\\frac{\\Big(1.0+-+\\frac{2.0}{3.0}\\times\\frac{d}{t}\\Big)}{(1.0+-+\\frac{2.0}{3.0}\\times\\frac{d}{t}/M)}= Sflowcalc\\times\\frac{\\Big(1.0+-+\\frac{2.0}{3.0}\\times\\frac{dcalc}{tcalc}\\Big)}{(1.0+-+\\frac{2.0}{3.0}\\times\\frac{dcalc}{tcalc}/Mcalc)}=Sfcalc |n|";
		else:
			Equation+="z\\ge20 |n|"
			Sf=Sflow*(1.0 - d/t);
			Equation+="S_f=S_f\\bigg(1-\\frac{d}{t}\\bigg)=Sflowcalc\\bigg(1-\\frac{dcalc}{tcalc}\\bigg)=Sfcalc |n|";

	else:	
		if(z<=50.0):
			Equation+="z\\leq50 |n|";
			M=(1 + 0.6275*z - 0.003375*z**2)**0.5;
			Equation+="M=\\sqrt{1+0.6275z-0.003375z^2}=\\sqrt{1+0.6275\\timeszcalc-0.003375\\timeszcalc^2}=Mcalc |n|";

		else:
			Equation+="z\\ge50 |n|"
			Equation+="M=0.032\\times z+3.3=0.032\\times zcalc+3.3=Mcalc |n|";
			M=0.032*z + 3.3;
		Equation+="S_f=S_{flow}\\times\\frac{1-0.85\\times\\frac{d}{t}}{1-0.85\\times\\frac{d}{t}/M}=Sflowcalc\\times\\frac{1-0.85\\times\\frac{dcalc}{tcalc}}{1-0.85\\times\\frac{dcalc}{tcalc}/Mcalc}=Sfcalc |n|";
		Sf=Sflow*(float(1-0.85*(float(d)/float(t)))/float(1-0.85*(float(d)/float(t))/M));
	
	PCalc=Sf*2*t/dia;
	Equation+="P=\\frac{S_f\\times2t}{D}=\\frac{Sfcalc\\times2\\timestcalc}{diacalc}=PCalc ";
	Equation=Equation.replace("zcalc",str(round(z,5)));
	Equation=Equation.replace("Mcalc",str(round(M,5)));
	Equation=Equation.replace("tcalc",str(t));
	Equation=Equation.replace("PCalc",str(PCalc));
	Equation=Equation.replace("dcalc",str(d));
	Equation=Equation.replace("diacalc",str(dia));
	Equation=Equation.replace("Lcalc",str(l));
	Equation=Equation.replace("Sfcalc",str(Sf));
	Equation=Equation.replace("Sflowcalc",str(Sflow));
	Equation=Equation.replace("Zanswer",str(round(z,5)));
	Equation=Equation.replace("Manswer",str(round(M,5)));
	

	return PCalc,Sf,Equation;
  
def calculateLevel2(param):
	return 0;
	
from sys import *;
from numpy import *;
import sys;
i=0;
param={};
form = cgi.FieldStorage();
if form.getvalue("Method"):
	param['Method']=form.getvalue("Method");
else:
	param['Method']='Modified';
param['Level']=1;

if form.getvalue("dia"):
	param['dia']=my_float(form.getvalue("dia"))
else:
	param['dia']=1;


if form.getvalue("d"):
	param['d']=my_float(form.getvalue("d"))
else:
	param['d']=1;

if form.getvalue("t"):
	param['t']=my_float(form.getfirst("t"));
else:
	param['t']=0.25;

if form.getvalue("l"):
	param['l']=form.getvalue("l");
else:
	param['l']=my_float(1);
if form.getvalue("Sflow"):
	param['Sflow']=form.getfirst("Sflow");
else:
	param['Sflow']=my_float(2000.0); 
if param['Level']==1:
	(MAWP,Sf,Equation)=calculateLevel1(param);
else:
	Sf=calculateLevel2(param);
	
print "Content-type: text/html";
print "";
print json.dumps({'Sf': Sf,'Equation':Equation,'MAWP':MAWP}, sort_keys=True, indent=4);