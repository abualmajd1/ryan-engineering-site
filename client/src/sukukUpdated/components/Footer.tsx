import { sukukUpdatedAsset } from '../asset';
import React from 'react';
import { PHONE_DISPLAY, PHONE_NUMBER } from '../data/siteData';

export const Footer: React.FC = () => {
  return (
    <footer className="bg-[#3F1620] py-12 border-t-2 border-rkGold/30 text-white">
      <div className="container mx-auto px-4 text-center">
        <div className="flex flex-col sm:flex-row items-center justify-between gap-6 max-w-5xl mx-auto mb-8 pb-8 border-b border-white/10">
          <div className="flex items-center gap-3 text-right">
            <div className="w-11 h-11 bg-white rounded-xl p-1 border border-rkGold/30 flex items-center justify-center shadow-sm">
              <img src={sukukUpdatedAsset("logo.png")} alt="شعار ريان" className="w-full h-full object-contain" />
            </div>
            <div>
              <div className="font-bold text-white text-base">مكتب ريان للمساحة والاستشارات الهندسية</div>
              <div className="text-xs text-rkGoldLight">الرياض</div>
            </div>
          </div>

          <div className="text-xs text-gray-400">
            هاتف خدمة العملاء:{' '}
            <a href={`tel:${PHONE_NUMBER}`} dir="ltr" className="inline-block text-rkGold font-bold tracking-wide hover:text-rkGoldLight transition-colors" aria-label={`الاتصال بخدمة العملاء ${PHONE_DISPLAY}`}>
              {PHONE_DISPLAY}
            </a>
          </div>
        </div>

        <p className="text-xs sm:text-sm text-gray-400">
          جميع الحقوق محفوظة &copy; 2026 مكتب ريان للمساحة والاستشارات الهندسية
        </p>
      </div>
    </footer>
  );
};
