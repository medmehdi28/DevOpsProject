from exchangelib import Credentials, Account, Configuration, Folder, DELEGATE, Message, EWSDateTime, EWSTimeZone, HTMLBody, FileAttachment, ItemAttachment
import exchangelib.errors as ee #optional for exchange exception handling
from selenium.webdriver.firefox.options import Options
from selenium import webdriver
from langdetect import detect, DetectorFactory #pip install langdetect for language recognition
#install all modules for pyhton
outlook = win32com.client.Dispatch("Outlook.Application")
mapi = outlook.GetNamespace("MAPI")
print('---- Connection Successfully Established ----')  
langues = {"it" : "Italian Language" , "fr" : "French Language", "ru" : "Russian Language" , "nl" : "Dutch Language", "es" : "Spanish Language", "de": "German Language", "en" : "English Language"}                                  
arrayLangues = ["it", "fr", "ru", "es","nl"] 
folder = mapi.Folders['youremailadresse'].Folders['Inbox']
subjArray = []
i = 0
while i < 1 :          
        for item in folder.Items :
                    try :                                                      
                            mess = item.Body
            
                            mess = 'no message available'
                                            

                            subj = item.subject
                            print('Subject : ' + subj)

                            subj = 'no subject available'
                            print(subj)                                  
                                

                            sender = item.SenderEmailAddress   
                            print('Sender : ' + sender)                                                                                        
                                                
                            sender = 'sender not found' 
                            print(sender)       

                            date = item.SentOn 
                            date = date.strftime('%d/%m/%Y %H:%M:%S')
                            print('Sending Date : ' + date)    
            
                            date = 'No date availabe' 
            
                            datenow = datetime.now()
                            datenow = datenow.strftime('%d/%m/%Y %H:%M:%S')       
                    except :
                            pass      



class ExchangeHandler(object):
    """description of class"""
    def __init__(self, smtp, user, pw):
      
        self.smtp=smtp
        self.user=user
        self.pw=pw        
        
        credentials = Credentials(user, pw)
        config = Configuration(server='outlook.office365.com', credentials=credentials)
        self.account = Account(primary_smtp_address=smtp, config=config,
                    autodiscover=False, access_type=DELEGATE)
        
        self.account.root.refresh()        
   
        self.root_path = self.account.root / 'Top of Information Store' / 'lab'
        self.inbox = self.account.inbox

    def has_mails(self, folder):
        self.folder = folder
        print ('Looking for mails...')
        #Check if there is any mail in the mailbox before trying to receive mails (optional function)
        if folder.total_count>0:
            return True
        else: 
            print ('Mailbox is empty')
            return False


class WebDriver(object):
    """description of class"""

    def __init__(self):
        #Setup Selenium headless mode in Firefox
        options = Options()
        options.headless = True
        self.driver=webdriver.Firefox(options=options)