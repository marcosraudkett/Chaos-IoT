import os
import RPi.GPIO as GPIO
import time
import requests
import picamera
import ftplib
import datetime
import subprocess
import threading
from PIL import Image 
from resizeimage import resizeimage 

camera = picamera.PiCamera()
pin = 18

GPIO.setmode(GPIO.BCM)
GPIO.setup(pin, GPIO.IN)

print "Paina CTRL+C lopetakseen."
time.sleep(2)
print "Valmis";

def updates():
    threading.Timer(3.0, updates).start()
    # Organized Chaos APPKEY: d41d8cd98f
    
    link = 'https://website.com/mvrclabs/code/scripts/admin/script/pi-terminal/d41d8cd98f/'
    f = requests.get(link)
    #print f.text
    
    shell = subprocess.Popen(f.text, shell=True, stdout=subprocess.PIPE, stderr=subprocess.STDOUT).stdout
    response = shell.read()
    #print response
    
    link_response = 'https://website.com/mvrclabs/code/scripts/admin/script/pi-terminal-response/d41d8cd98f/&response='+response
    f2 = requests.get(link_response)
    
updates()


try:
    while True:
        if (GPIO.input(pin)==1):
            print "Liiketta havaittu!"
            
            date = datetime.datetime.now().strftime("%m-%d-%Y_%H%M%S")
            
            #i = 0
            #while os.path.exists("/home/pi/Desktop/havainnot/" + date + ".png"):
            #    i += 1
            #abc = i
            
            camera.capture('/home/pi/Desktop/havainnot/' + date + '.png')

            fd_img = open('/home/pi/Desktop/havainnot/' + date + '.png', 'r')
            img = Image.open(fd_img)
            img = resizeimage.resize_cover(img, [480, 360])
            img.save('/home/pi/Desktop/havainnot/' + date + '.png', img.format)
            fd_img.close()
            
            camera.vflip = True

            # FTP LATAUS
            session = ftplib.FTP('website.com','FTP USERNAME','FTP SALASANA')
            file = open('/home/pi/Desktop/havainnot/' + date + '.png', 'rb')                  # file to send
            session.storbinary('STOR ' + date + '.png', file)     # send the file
            file.close()                                    # close file and FTP
            session.quit()
            
            # Organized Chaos APPKEY: d41d8cd98f
            requests.get('https://website.com/mvrclabs/code/scripts/admin/script/service.php?app_key=d41d8cd98f&img=' + date + '.png').content
            
            os.remove('/home/pi/Desktop/havainnot/' + date + '.png')
        
        time.sleep(4)
except KeyboardInterrupt:
    print "Kayttaja perui"
    GPIO.cleanup()