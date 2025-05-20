import asyncio
from database import engine, Base
import models  

async def init():
    async with engine.begin() as conn:
        await conn.run_sync(Base.metadata.create_all)

    print("✅ Tablas creadas exitosamente.")

if __name__ == "__main__":
    asyncio.run(init())
