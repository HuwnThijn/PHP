from pymongo import MongoClient
import pymysql
import os
from dotenv import load_dotenv

mongo_uri = "mongodb://127.0.0.1:27017/DermatologyClinic"

def connect_to_mongodb():
    try:
        mongo_client = MongoClient(mongo_uri)
        
        mongo_client.admin.command('ping')
        print("MongoDB kết nối thành công!")
        
        db = mongo_client.get_database()
        
        return mongo_client, db
    except Exception as e:
        print(f"Không thể kết nối đến MongoDB: {e}")
        return None, None

def connect_to_mysql():
    """Test connection to MySQL and return connection status"""
    try:
        # Load environment variables
        load_dotenv()
        
        # Connect to MySQL
        conn = pymysql.connect(
            host=os.getenv('DB_HOST', 'localhost'),
            user=os.getenv('DB_USERNAME', 'root'),
            password=os.getenv('DB_PASSWORD', ''),
            db=os.getenv('DB_DATABASE', 'beauty_clinic'),
            charset='utf8mb4',
            cursorclass=pymysql.cursors.DictCursor
        )
        
        # Test connection with a simple query
        with conn.cursor() as cursor:
            cursor.execute("SELECT 1 AS connection_test")
            result = cursor.fetchone()
        
        conn.close()
        return True, "MySQL connection successful"
    except Exception as e:
        return False, f"MySQL connection failed: {str(e)}"
